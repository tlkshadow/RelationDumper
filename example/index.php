<?php
ini_set('xdebug.max_nesting_level', 1000);


include '../vendor/autoload.php';

$connection = new PDO('mysql:host=localhost;dbname=predrinks;charset=utf8', 'test', 'test');
$dataCollector = new \RelationDumper\DataCollector\MySQL\PdoDataCollector($connection);
$dataCollector->setTable('event');
$dataCollector->setPrimaryKeyValue('1');

$relationDumper = new \RelationDumper\RelationDumper(
    $dataCollector,
    new \RelationDumper\Output\MySQL\InsertOutput()
);

$data = $relationDumper->execute();

var_dump(
    $data
);
