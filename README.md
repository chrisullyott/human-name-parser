# human-name-parser

A human name parser written in PHP.

Based on Josh Fraser's [PHP-Name-Parser](https://github.com/joshfraser/PHP-Name-Parser).

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
    [full] => Doctor Martin luther King Jr
    [full_clean] => Dr. Martin Luther King Jr.
    [salutation] => Dr.
    [first] => Martin
    [middle] => Luther
    [last] => King
    [suffix] => Jr.
)
```
