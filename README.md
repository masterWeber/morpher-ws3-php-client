# morpher-ws3-php-client

PHP-клиент веб-сервиса "Морфер" 3.0

![GitHub Workflow Status](https://img.shields.io/github/workflow/status/masterWeber/morpher-ws3-php-client/PHP%20Composer)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/masterWeber/morpher-ws3-php-client)
![Packagist Downloads](https://img.shields.io/packagist/dt/masterweber/morpher-ws3-php-client)
![GitHub](https://img.shields.io/github/license/masterWeber/morpher-ws3-php-client)
и [платное](https://morpher.ru/ws3/buy/) использование. Подробнее смотрите на [сайте проекта](https://morpher.ru).

## Установка

```sh
composer require masterweber/morpher-ws3-php-client
```

## Использование

```php
use Morpher\Morpher;

$morpher = new Morpher(
    'https://localhost',    // по умолчанию https://ws3.morpher.ru
    'YOUR_TOKEN',           // по умолчанию null
    1.0                     // по умолчанию 3.0 сек
);
```

Можно вызвать конструктор без аргументов, в этом случае будут использоваться параметры по умолчанию.

```php
use Morpher\Morpher;

 $morpher = new Morpher();
```

## License

[MIT](LICENSE)