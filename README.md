# human-name-parser

A human name parser written in PHP.

Based on Josh Fraser's [PHP-Name-Parser](https://github.com/joshfraser/PHP-Name-Parser). Here, I attempted to rewrite the library to be more understandable and maintainable. Just about all of the original features are maintained.

### Installation

Include in your project, or, install with Composer:

```
composer require chrisullyott/human-name-parser
```

### Parsing a name

```
$name = 'Doctor Martin luther King Jr';

$parser = new HumanNameParser($name);
$result = $parser->parse();
```

### Example result

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
