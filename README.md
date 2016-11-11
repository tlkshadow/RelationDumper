```
<?php

include 'vendor/autoload.php';

$connection = new PDO('mysql:host=localhost;dbname=predrinks;charset=utf8', 'root', 'PW');
$dataCollector = new \RelationDumper\DataCollector\MySqlDataCollector($connection);
$dataCollector->setTable('event');
$dataCollector->setPrimaryKeyValue('1');

$relationDumper = new \RelationDumper\RelationDumper(
    $dataCollector,
    new \RelationDumper\Strategy\ArrayStrategy()
);

var_dump(
    $relationDumper->execute()
);
```