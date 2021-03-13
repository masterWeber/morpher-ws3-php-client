# morpher-ws3-php-client

PHP-клиент веб-сервиса "Морфер" 3.0

![GitHub Workflow Status](https://img.shields.io/github/workflow/status/masterWeber/morpher-ws3-php-client/PHP%20Composer)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/masterWeber/morpher-ws3-php-client)
![Packagist Downloads](https://img.shields.io/packagist/dt/masterweber/morpher-ws3-php-client)
![GitHub](https://img.shields.io/github/license/masterWeber/morpher-ws3-php-client)

### Библиотека реализует следующие функции

(с помощью веб-сервиса ["Морфер 3.0"](https://morpher.ru/ws3/))

#### На русском языке:

* [Склонение по падежам](#russian-declension);
* [Выделение в строке фамилии, имени и отчества](#russian-full-name);
* [Пропись чисел и склонение единицы измерения](#russian-spell) (3 новых 
  письма, 10 комментариев);
* [Пропись чисел в виде порядковых числительных](#russian-spell-ordinal) («сто 
  первый километр»);
* [Пропись дат в любом падеже](#russian-spell-date) («пятого мая две тысячи 
  первого года»);
* [Склонение прилагательных по родам](#russian-adjective-genders);
* [Образование прилагательных от названий городов и стран](#russian-adjectivize);
* [Расстановка ударений в текстах](#add-stress-marks).

#### На украинском языке:

* [Склонение по падежам](#ukrainian-declension);
* [Пропись чисел и склонение единицы измерения](#ukrainian-spell) (3 рубля, 
  10 коментарів).

#### На казахском языке:

* [Склонение по падежам, числам и лицам](#qazaq-declension).

#### Общие:

* [Остаток запросов на данный момент](#get-queries-left).

### Пользовательский словарь

Веб-сервис поддерживает исправление склонения по требованию пользователя. Для 
этого имеются 3 метода:

* [Получить список всех добавленных исправлений](#userdict-get);
* [Добавить или изменить исправление](#userdict-post);
* [Удалить исправление](#userdict-delete).

Веб-сервис ["Морфер 3.0"](https://morpher.ru/ws3/)
предусматривает [бесплатное (с ограничениями)](https://morpher.ru/ws3/#limitations)
и [платное](https://morpher.ru/ws3/buy/) использование. Подробнее смотрите 
на [сайте проекта](https://morpher.ru).

## Установка

```sh
composer require masterweber/morpher-ws3-php-client
```

## Использование

```php
use Morpher\Morpher;

$morpher = new Morpher(
    'https://localhost', // по умолчанию https://ws3.morpher.ru
    'YOUR_TOKEN',        // по умолчанию null
    1.0                  // по умолчанию 3.0 сек
);
```

Можно вызвать конструктор без аргументов, в этом случае будут использоваться 
параметры по умолчанию.

```php
use Morpher\Morpher;

$morpher = new Morpher();
```

<p id="russian-declension"></p>

### Склонение по падежам на русском языке

Для склонения слов и словосочетаний используется метод 
`russian->declension(string $phrase, string ...$flags)`:

```php
$result = $morpher->russian->declension('Программист');

echo $result->genitive;         // Программиста
echo $result->plural->genitive; // Программистов
```

`$result` &mdash; объект `DeclensionResult` со следующими свойствами:

* `nominative` &mdash; текст в именительном падеже;
* `genitive` &mdash; текст в родительном падеже;
* `dative` &mdash; текст в дательном падеже;
* `accusative` &mdash; текст в винительном падеже;
* `instrumental` &mdash; текст в творительном падеже;
* `prepositional` &mdash; текст в предложном падеже;
* `plural` &mdash; объект со свойствами-падежами для текста во множественном 
  числе.

При использовании платного аккаунта на сервисе, определяются дополнительные 
свойства:

* `prepositional_O` — предложный падеж с предлогом О/ОБ/ОБО, предлог выбирается 
  автоматически;
* `gender` &mdash; род (мужской, женский или средний);
* `where`, `locative` — в местном падеже (локатив) с предлогом;
* `where_to` — в направительном падеже (аллатив) с предлогом;
* `where_from`, `whence` — в исходном падеже (аблатив) с предлогом.

#### Флаги для разрешения неоднозначностей

Есть слова, которые могут склоняться по-разному, например:

* фамилия Резник склоняется у мужчин и не склоняется у женщин;
* Ростов в творительном падеже будет Ростовым, если это фамилия, и Ростовом, 
  если это город;
* тестер в винительном падеже будет тестера, если это человек, и тестер, если 
  имеется в виду прибор.

Для повышения качества склонения вы можете сообщить веб-сервису дополнительную 
информацию через флаги. Несколько флагов можно передать через запятую:

```php
use Morpher\Russian\RussianClient;

$result = $morpher->russian->declension(
    'Слепов Сергей Николаевич', 
    RussianClient::FLAG_NAME, 
    RussianClient::FLAG_MASCULINE
);

echo $result->genitive;       // Слепова Сергея Николаевича
echo $result->fullName->name; // Сергей
```

Флаги поддерживаемые для `russian->declension(string $phrase, string ...$flags)`:

* RussianClient::FLAG_FEMININE &mdash; Женский род;
* RussianClient::FLAG_MASCULINE &mdash; Мужской род;
* RussianClient::FLAG_ANIMATE &mdash; Одушевлённое;
* RussianClient::FLAG_INANIMATE &mdash; Неодушевлённое;
* RussianClient::FLAG_COMMON &mdash; Нарицательное;
* RussianClient::FLAG_NAME &mdash; ФИО.

<p id="russian-full-name"></p>

### Выделение в строке фамилии, имени и отчества

Если входная строка распознана как ФИО, то объект `DeclensionResult` будет 
содержать разбивку строки на фамилию, имя и отчество:

* `genitive` &mdash; ФИО в родительном падеже;
* `dative` &mdash; ФИО в дательном падеже;
* `accusative` &mdash; ФИО в винительном падеже;
* `instrumental` &mdash; ФИО в творительном падеже;
* `prepositional` &mdash; ФИО в предложном падеже;
* `name` &mdash; Имя в именительном падеже;
* `surname` &mdash; Фамилия в именительном падеже;
* `patronymic` &mdash; Отчество в именительном падеже.

<p id="russian-spell"></p>

### Пропись чисел и согласование с числом

Метод `russian->spell(int $number, string $unit)` решает задачу получения 
прописи числа (тысяча сто двадцать пять) и согласование единицы измерения с 
предшествующем числом (1 попугай, 2 попугая, 5 попугаев):

```php
$result = $morpher->russian->spell(235, 'рубль');

echo $result->n->genitive;    // двухсот тридцати пяти
echo $result->unit->genitive; // рублей
```

Комбинируя соответствующие падежные формы n и unit, можно получить вывод 
«суммы прописью» на любой вкус:

* 235 рублей
* Двести тридцать пять рублей
* 235 (двести тридцать пять) рублей и т.п.

<p id="russian-spell-ordinal"></p>

### Пропись чисел в виде порядковых числительных

Метод `russian->spellOrdinal(int $number, string $unit)` похож 
на `russian->spell(int $number, string $unit)`, но возвращает пропись числа в 
форме порядкового числительного:

```php
$result = $morpher->russian->spellOrdinal(5, 'колесо');

echo $result->n->genitive;    // пятого
echo $result->unit->genitive; // колеса
```

<p id="russian-spell-date"></p>

### Пропись дат

Метод `russian->spellDate(DateTime $date)` склоняет по падежам дату:

```php
$date = new DateTime();
$result = $morpher->russian->spellDate($date);

echo $result->genitive;     // двадцать девятого июня две тысячи девятнадцатого года
echo $result->instrumental; // двадцать девятому июня две тысячи девятнадцатого года
```

<p id="russian-adjective-genders"></p>

### Склонение прилагательных по родам

Метод `russian->adjectiveGenders(string $adjective)` склоняет данное ему 
прилагательное, преобразуя его из мужского рода в женский, средний и во 
множественное число:

```php
$result = $morpher->russian->adjectiveGenders('уважаемый');

echo $result->feminine; // уважаемая
echo $result->neuter;   // уважаемое
echo $result->plural;   // уважаемые
```

Требования к входному прилагательному:

* Оно должно быть в мужском роде, в единственном числе.
* Оно должно быть полным, т.е. "полный", а не "полон".
* Оно должно быть одним словом. Внутри слова допустимы дефис и апостроф: 
  рабоче-крестьянский, Кот-д'Ивуарский. Вокруг слова допустимы пробелы, кавычки 
  и другие знаки.

<p id="russian-adjectivize"></p>

### Образование прилагательных

Метод `russian->adjectivize(string $lemma)` образует прилагательные от названий 
городов и стран: Москва – московский, Ростов – ростовский, Швеция – шведский, 
Греция – греческий.

Пример:

```php
$result = $morpher->russian->adjectivize('Москва');

print_r($result); // ['московский']
```

Метод возвращает массив строк. Что они означают, описано 
[здесь](https://morpher.ru/adjectivizer/).

<p id='add-stress-marks'></p>

### Расстановка ударений в текстах

Метод `russian->addStressMarks(string $text)` расставляет ударения в текстах на 
русском языке:

```php
$result = $morpher->russian->addStressMarks('Три девицы под окном');

echo $result; // Три деви́цы под окно́м
```

Ударение отмечается символом с кодом U+0301, который вставляется сразу после 
ударной гласной. Односложные слова не получают знака ударения, за исключением 
случаев, когда предлог или частица несет на себе ударение: за́ руку, не́ за что.
Варианты прочтения разделяются вертикальной чертой, например:

```php
$result = $morpher->russian->addStressMarks('Белки питаются белками');

echo $result; // Бе́лки|Белки́ пита́ются бе́лками|белка́ми
```

<p id='ukrainian-declension'></p>

### Склонение по падежам на украинском языке

Украинский вариант склонения &mdash; метод 
`ukrainian->declension(string $phrase, string ...$flags)`:

```php
$result = $morpher->ukrainian->declension('Крутько Катерина Володимирiвна');

echo $result->genitive; // Крутько Катерини Володимирівни
echo $result->dative;   // Крутько Катерині Володимирівні
echo $result->vocative; // Крутько Катерино Володимирівно
```

`$result` &mdash; объект `DeclensionResult` со следующими свойствами:

* `nominative` &mdash; текст в именительном падеже;
* `genitive` &mdash; текст в родительном падеже;
* `dative` &mdash; текст в дательном падеже;
* `accusative` &mdash; текст в винительном падеже;
* `instrumental` &mdash; текст в творительном падеже;
* `prepositional` &mdash; текст в местном падеже;
* `vocative` &mdash; текст в звательном падеже.

При использовании платного аккаунта на сервисе, определяются дополнительные 
свойства:

* `gender` &mdash; род (чоловічий, жіночий);

Украинская версия пока обрабатывает только имена, фамилии и отчества.

#### Флаги для разрешения неоднозначностей

```php
use Morpher\Ukrainian\UkrainianClient;

$result = $morpher->ukrainian->declension('Карен', UkrainianClient::FLAG_FEMININE);

echo $result->genitive; // Карен (женское имя не склоняется)
```

Флаги поддерживаемые для `ukrainian->declension(string $phrase, string ...$flags)`:

* UkrainianClient.FLAG_FEMININE &mdash; Женский род;
* UkrainianClient.FLAG_MASCULINE &mdash; Мужской род;
* UkrainianClient.FLAG_NEUTER &mdash; Средний род;
* UkrainianClient.FLAG_PLURAL &mdash; Множественное число.

<p id="ukrainian-spell"></p>

### Пропись чисел и согласование с числом на украинском языке

Метод `ukrainian->spell(int $number, string $unit)` решает задачу получения 
прописи числа (одна тисяча сто двадцять п'ять) и согласование единицы измерения 
с предшествующем числом (один рубль, два рубля, п'ять
рублів):

```php
$result = $morpher->ukrainian->spell(235, 'рубль');

echo $result->n->genitive;    // двохсот тридцяти п'яти
echo $result->unit->genitive; // рублів
```

<p id='qazaq-declension'></p>

### Склонение по падежам, числам и лицам на казахском языке

Для склонения слов и словосочетаний используется метод 
`qazaq->declension(string $phrase)`:

```php
$result = $morpher->qazaq->declension('менеджер');

echo $result->genitive;                            // менеджердің
echo $result->plural->genitive;                    // менеджерлердің
echo $result->plural->firstPersonPlural->genitive; // менеджерлеріміздің
```

`$result` &mdash; объект `DeclensionResult` со следующими свойствами:

* `nominative` &mdash; текст в именительном падеже;
* `genitive` &mdash; текст в родительном падеже;
* `dative` &mdash; текст в дательно-направительном падеже;
* `accusative` &mdash; текст в винительном падеже;
* `ablative` &mdash; текст в исходном падеже;
* `locative` &mdash; текст в местном падеже;
* `instrumental` &mdash; текст в творительном падеже;
* `plural` &mdash; возвращает объект со свойствами-падежами для текста во 
  множественном числе.

<p id='get-queries-left'></p>

### Остаток запросов

Метод `getQueriesLeft()` возвращает остаток запросов на данный момент. 
Лимит на запросы восстанавливается в `00:00 UTC`.

```php
$result = $morpher->getQueriesLeft();

echo $result; // 100
```

<p id="userdict-get"></p>

### Получить список исправлений

Для того чтобы получить список всех исправлений, нужно использовать метод 
`russian->userDict->getAll()`
или `ukrainian->userDict->getAll()`:

```php
$result = $morpher->russian->userDict->getAll();

print_r($result); // Массив с объектами CorrectionEntry
```

Объект `СorrectionEntry` со следующими свойствами:

* `singular` &mdash; объект `CorrectionForms` с формами в единственном числе;
* `plural`   &mdash; объект `CorrectionForms` с формами во множественном числе;
* `gender`   &mdash; род (мужской, женский или средний).

Объект `CorrectionForms` со следующими свойствами:

* `nominative` &mdash; текст в именительном падеже;
* `genitive` &mdash; текст в родительном падеже;
* `dative` &mdash; текст в дательном падеже;
* `accusative` &mdash; текст в винительном падеже;
* `instrumental` &mdash; текст в творительном падеже;
* `prepositional` &mdash; текст в предложном падеже;
* `locative` &mdash; текст в местном падеже;

Для украинского языка:

Объект `СorrectionEntry` со следующими свойствами:

* `singular` &mdash; объект `CorrectionForms` с формами в единственном числе;
* `gender`   &mdash; род (чоловічий, жіночий).

Объект `CorrectionForms` со следующими свойствами:

* `nominative` &mdash; текст в именительном падеже;
* `genitive` &mdash; текст в родительном падеже;
* `dative` &mdash; текст в дательном падеже;
* `accusative` &mdash; текст в винительном падеже;
* `instrumental` &mdash; текст в творительном падеже;
* `prepositional` &mdash; текст в местном падеже;
* `vocative` &mdash; текст в звательном падеже.

<p id="userdict-post"></p>

### Добавить или изменить исправление

Для добавления или изменения исправления использовать метод
`russian->userDict->addOrUpdate(CorrectionEntry $entry)`.

```php
use Morpher\Russian\CorrectionEntry;
use Morpher\Russian\CorrectionForms;

$singular = new CorrectionForms();
$singular->nominative = 'Кошка';
$singular->dative = 'Пантере';

$plural = new CorrectionForms();
$plural->dative = 'Пантерам';

$entry = new CorrectionEntry();

$morpher->russian->userDict->addOrUpdate($entry); // true
```

Для украинского языка:

```php
use Morpher\Ukrainian\CorrectionEntry;
use Morpher\Ukrainian\CorrectionForms;

$singular = new CorrectionForms();
$singular->nominative = 'Кiшка';
$singular->dative = 'Пантерi';

$entry = new CorrectionEntry();

$morpher->ukrainian->userDict->addOrUpdate($entry); // true
```

<p id="userdict-delete"></p>

### Удаление исправления

Для того чтобы удалить исправление, достаточно передать строку в именительном 
падеже в метод `russian->userDict->remove(string $nominativeForm)`:

```php
$morpher->russian->userDict->remove('Кошка'); // true
```

Для украинского языка:

```php
$morpher->ukrainian->userDict->remove('Кiшка'); // true
```

## Разработка

PHP: Убедитесь, что [установлена](https://www.php.net/downloads) версия PHP не 
ниже ^7.4. Проверить это можно с помощью `php -v`.

### Установка

Сделайте форк репозитория `morpher-ws3-php-client`.

Затем выполните:

```sh
$ git clone git@github.com:<your-github-username>/morpher-ws3-php-client.git
$ cd morpher-ws3-php-client
$ composer install
```

### Запуск тестов

```sh
$ composer test
```

### Выпуск нового релиза

* [Добавить новый релиз](https://github.com/masterWeber/morpher-ws3-php-client/releases) на GitHub.

Примерно через минуту новый пакет должен появиться
на [packagist.org](https://packagist.org/packages/masterweber/morpher-ws3-php-client).

## License

[MIT](LICENSE)
