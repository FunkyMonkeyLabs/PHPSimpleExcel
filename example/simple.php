<?php

require_once __DIR__.'/../src/FunkyMonkeyLabs/PHPSimpleExcel.php';
require_once __DIR__.'/../src/FunkyMonkeyLabs/Element/ElementInterface.php';
require_once __DIR__.'/../src/FunkyMonkeyLabs/Element/Row.php';

$excel = new FunkyMonkeyLabs\PHPSimpleExcel();
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

echo $excel->render('excel');