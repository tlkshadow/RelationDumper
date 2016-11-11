<?php

namespace RelationDumper\DataCollector;

use PDO;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
class MySqlDataCollector implements DataCollectorInterface
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
    private function getForeignKeyInformation($table, $parentAttributes)
    {
        $foreignKeys = $this->getForeignKeys($table);

        $result = [];
        $parentTable = [];
        foreach ($foreignKeys as $foreignKey) {
            $parentTable[] = $table;
            $table = $foreignKey['REFERENCED_TABLE_NAME'];
            $pk = $foreignKey['REFERENCED_COLUMN_NAME'];

            $result[implode('_', $parentTable).'_'.$table] = $parentAttributes;
            $result = array_merge($result, $this->getForeignKeyInformation($table, $parentAttributes));
            if (array_key_exists($foreignKey['COLUMN_NAME'], $parentAttributes)) {
                $parentAttributes = $this->getAttributes($table, $pk, $parentAttributes[$foreignKey['COLUMN_NAME']]);
            }
        }

        return $result;
    }

    /**
     * @param string $table
     *
     * @return array
     */
    private function getForeignKeys($table)
    {
        $sql = sprintf(
            "SELECT 
              TABLE_NAME,COLUMN_NAME,CONSTRAINT_NAME, REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
             FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
             WHERE REFERENCED_TABLE_SCHEMA IS NOT NULL AND TABLE_NAME = '%s'",
            $table
        );

        $stm = $this->connection->prepare($sql);
        $stm->execute();

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
