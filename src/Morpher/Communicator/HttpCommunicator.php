<?php


namespace Morpher\Communicator;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;

class HttpCommunicator implements Communicator
{
    protected ?string $token = null;
    protected string $baseUrl = 'https://ws3.morpher.ru';
    protected float $timeout = 3.0;
    protected ClientInterface $httpClient;

    public function __construct(
      ?string $token = null,
      ?string $baseUrl = null,
      ?int $timeoutMs = null
    ) {
        if ($token) {
            $this->token = $token;
        }

        if ($baseUrl) {
            $this->baseUrl = $baseUrl;
        }

        if ($timeoutMs) {
            $this->timeout = (float)$timeoutMs / 1000;
        }

        $this->httpClient = new Client();
    }

    public function request(string $path, array $params, string $method)
    {
        $isContentBody = $this->isContentBody($params, $method);

        $requestParameters = $isContentBody
          ? $params[self::CONTENT_BODY_KEY]
          : $this->buildRequestParams($params);

        $uri = $this->isPost($method)
          ? $this->buildUri($path)
          : $this->buildUri($path, $params);

        $contentType = $isContentBody
          ? 'text/plain; charset=utf-8'
          : 'application/x-www-form-urlencoded';

        $options = [
          'headers' => [
            'Content-Type' => $contentType,
            'Accept' => 'application/json',
          ],
          'timeout' => $this->timeout,
        ];

        if ($this->isPost($method)) {
            $options['body'] = $requestParameters;
        }


        try {
            $response = $this->httpClient->request($method, $uri, $options);

            return json_decode($response->getBody());
        } catch (ClientExceptionInterface $e) {

            $response = $e->getResponse();

            $statusCode = $response->getStatusCode();
            $data = json_decode($response->getBody());

            $msg = $data->message;
            $code = $data->code;

            switch ($statusCode) {
                case 402:
                    throw new DailyLimitExceededException($msg, $code);
                case 403:
                    throw new IpBlockedException($msg, $code);
                case 497:
                    throw new InvalidTokenFormatException($msg, $code);
                case 498:
                    throw new TokenNotFoundException($msg, $code);
                case 500:
                    throw new ServerErrorException($msg, $code);
                default:
                    throw new InvalidServerResponseException($statusCode, $msg, $code);
            }
        }
    }

    protected function buildUri(string $path, array $params = []): string
    {
        $url = "{$this->baseUrl}{$path}";

        if ($this->token) {
            $params['token'] = $this->token;
        }

        if ($params) {
            $url .= '?' . $this->buildRequestParams($params);
        }

        return $url;
    }

    protected function buildRequestParams(array $params): string
    {
        return http_build_query($params);
    }

    protected function setHttpClient(ClientInterface $client): void
    {
        $this->httpClient = $client;
    }

    protected function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    protected function isContentBody(array $params, string $method): bool
    {
        return $this->isPost($method) && count($params) === 1
          && array_key_exists(self::CONTENT_BODY_KEY, $params);
    }

    protected function isPost(string $method): bool
    {
        return mb_strtoupper($method) === self::METHOD_POST;
    }
}