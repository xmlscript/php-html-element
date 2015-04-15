# PHP HTML Element

Create and modify HTML tags in PHP the simple way with OOP classes

## Installation

Installation of this module uses Composer. For Composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

Put the following into your composer.json

    {
        "require": {
            "bockmist/html-element": "dev-master"
        }
    }


## Examples

### Simple Tags

```php
<?php

$link = new Tag('a');
$link->href = 'http://www.google.com';
$link->setText('Google this!');

echo $link->render();
```

Output:

```html
<a href="http://www.google.com">Google this!</a>
```

### Tag hierarchy

```php
<?php

$data = array( array('1st row, column 1', '1st row, column 2'), array('2nd row, column 1', '2nd row, column 2') );

$table = new Tag('table');

foreach ( $data as $row )
{
    $tr = new Tag('tr');
    $table->addChild( $tr );
    
    foreach ( $row as $cell )
    {
        $td = new Tag('td');
        $td->setText( $cell );
        
        $tr->addChild( $td );
    }
}

echo $table;
```

Output:

```html
<table><tr><td>1st row, column 1</td><td>1st row, column 2</td></tr><tr><td>2nd row, column 1</td><td>2nd row, column 2</td></tr></table>
```

### Parse HTML

```php
<?php

$html = "<div><h1>Hello world</h1>How do you do?</div>";

$div = Element::createFromString($html);

$div->getChild(0)->setAttribute('style', 'font-weight:25px')->setText('Hello there!');

echo $div;
```

Output:

```html
<div><h1 style="font-weight:25px">Hello there!</h1>How do you do?</div>
```


