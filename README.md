# human-name-parser

A human name parser written in PHP.

Based on Josh Fraser's [PHP-Name-Parser](https://github.com/joshfraser/PHP-Name-Parser).

### Installation

Include in your project, or, install with Composer:

```
"require": {
    "chrisullyott/human-name-parser": "dev-master"
},
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/chrisullyott/human-name-parser"
    }
]
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
