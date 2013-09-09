FunkyMonkeyLabs\PHPSimpleExcel by [FunkyMonkeyLabs](https://github.com/funkymonkeylabs)
==============

PHPSimpleExcel is a library basing on [oliverschwarz/php-excel](https://github.com/oliverschwarz/php-excel).
This implementation allow to you to controlling current row by `startRow()` method, which returns Row object.

Instalation
-----------

FunkyMonkeyLabs\PHPSimpleExcel is available on packagist.org (composer), so you can simply add:

```
    "require": {
        "funkymonkeylabs/phpsimpleexcel" : "v1.0"
    }
```

Usage
-----

```php
<?php

require_once __DIR__.'/../src/PHPSimpleExcel.php';
require_once __DIR__.'/../src/Element/ElementInterface.php';
require_once __DIR__.'/../src/Element/Row.php';

$excel = new PHPSimpleExcel\PHPSimpleExcel();
$excel->addNamedStyle('idFormat', array(
        "NumberFormat" => array(
            "ss:Format" => "##00"
        )
    ));
$excel->addRow(array(
        "id", "Column1", "Column2"
    ));

foreach(range(0,11) as $id) {
    $excel
        ->startRow()
        ->addCell($id, 'Number', 'idFormat')
        ->addCell('value'.$id)
        ->addCell('value2'.$id)
        ->end();
}
echo $excel->generateXML('excel');
```