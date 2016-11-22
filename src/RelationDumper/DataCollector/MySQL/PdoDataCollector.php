<?php

namespace RelationDumper\DataCollector\MySQL;

use ArrayObject;
use PDO;
use RelationDumper\DataCollector\DataCollectorInterface;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
class PdoDataCollector implements DataCollectorInterface
{
    /**
     * @var string
     */
    private $table;

    /**
     * @var int|string
     */
    private $pkValue;

    /**
     * @var int
     */
    private $depth = 1;

    /**
     * @var int
     */
    private $currentDepth = 0;

    /**
     * @var PDO
     */
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $result = [];
        $pk = $this->getPrimaryKeyFromTable($this->table);

        $parentAttributes = $this->getAttributes($this->table, $pk, $this->pkValue);

        $result[$this->table] = $parentAttributes;

        return array_reverse(array_merge($result, $this->getForeignKeyInformation($this->table, $parentAttributes)));
    }

    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * @param string $table
     *
     * @return $this
     */
    public function setTable($table)
    {
        $this->table = $table;

        return $this;
    }

    /**
     * @param int|string $value
     *
     * @return $this
     */
    public function setPrimaryKeyValue($value)
    {
        $this->pkValue = $value;

        return $this;
    }

    /**
     * @param string $table
     * @param array $parentAttributes
     *
     * @return array
     */
    private function getForeignKeyInformation($currentTable, $parentAttributes)
    {
        $foreignKeys = $this->getAllForeignKeysFromTable($currentTable);

        $result = [];

        $parentTable = [];
        $arrayObject = new ArrayObject( $foreignKeys );
        foreach ($arrayObject->getIterator() as $foreignKey) {
            if (!in_array($currentTable, $parentTable)) {
                $parentTable[] = $currentTable;
            }
            $pk = $foreignKey['REFERENCED_COLUMN_NAME'];
            $table = $foreignKey['REFERENCED_TABLE_NAME'];

            $attributes = $this->getAttributes($table, $pk, $parentAttributes[$foreignKey['COLUMN_NAME']]);
            $result[implode(self::DATA_SEPARATOR, $parentTable).self::DATA_SEPARATOR.$table] = $attributes;

            /*
             // go deeper for the next table
             $result = array_merge($result, $this->getForeignKeyInformation($table, $parentAttributes));
            $parentAttributes = [];
            if (array_key_exists($foreignKey['COLUMN_NAME'], $parentAttributes)) {
                $parentAttributes = ;
            }*/
        }

        var_dump($this->currentDepth);

        return $result;
    }

    /**
     * @param string $table
     *
     * @return array
     */
    private function getAllForeignKeysFromTable($table)
    {
        $sql =
            "SELECT 
              TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
             FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
             WHERE REFERENCED_TABLE_SCHEMA IS NOT NULL AND TABLE_NAME = '".$table."'";

        $stm = $this->connection->prepare($sql);

        $stm->execute();

        $this->currentDepth++;

        return $stm->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $table
     * @param string $pk
     * @param mixed $pkValue
     *
     * @return array
     */
    private function getAttributes($table, $pk, $pkValue)
    {
        $sql = sprintf("SELECT * FROM `%s` WHERE %s = '%s'", $table, $pk, $pkValue);

        $stm = $this->connection->prepare($sql);
        $stm->execute();

        return $stm->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $table
     *
     * @return string
     */
    private function getPrimaryKeyFromTable($table)
    {
        $sql = <<<SQL
SELECT k.COLUMN_NAME
FROM information_schema.table_constraints t
LEFT JOIN information_schema.key_column_usage k
USING(constraint_name,table_schema,table_name)
WHERE t.constraint_type='PRIMARY KEY'
    AND t.table_schema=DATABASE()
    AND t.table_name='$table';
SQL;

        $stm = $this->connection->prepare($sql);
        $stm->execute();

        return $stm->fetchColumn(0);

    }
}
