# human-name-parser

[![Latest Stable Version](https://poser.pugx.org/chrisullyott/human-name-parser/v/stable)](https://packagist.org/packages/chrisullyott/human-name-parser)
[![Total Downloads](https://poser.pugx.org/chrisullyott/human-name-parser/downloads)](https://packagist.org/packages/chrisullyott/human-name-parser)

A human name parser written in PHP.

Based on Josh Fraser's [PHP-Name-Parser](https://github.com/joshfraser/PHP-Name-Parser). I attempted to rewrite the library to be more easily understandable (at least to myself). Nearly all the original features are maintained.

The algorithm first sanitizes a name string, and then breaks it into smaller pieces using a library of professional titles and suffixes.

### Installation

Include in your project, or, install with [Composer](https://getcomposer.org/):

```bash
$ composer require chrisullyott/human-name-parser
```

### Parsing a name

```php
use ChrisUllyott\HumanNameParser;

$parser = new HumanNameParser('Dr. martin luther king jr');
print_r($parser->parse());
```

```
Array
(
    [full]       => Dr. Martin Luther King Jr.
    [salutation] => Dr.
    [first]      => Martin
    [middle]     => Luther
    [last]       => King
    [suffix]     => Jr.
)
```

### Methods

| Name | Sample output |
|---|---|
| getFullName() | _Dr. Martin Luther King Jr._ |
| getSalutation() | _Dr._ |
| getFirstName() | Martin |
| getMiddleName() | Luther |
| getLastName() | King |
| getSuffix() | Jr. |
