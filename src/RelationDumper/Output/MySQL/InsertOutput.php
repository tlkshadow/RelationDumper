<?php

namespace RelationDumper\Output\MySQL;

use RelationDumper\DataCollector\DataCollectorInterface;
use RelationDumper\Output\OutputInterface;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
class InsertOutput implements OutputInterface
{
    use MySqlHelperTrait;

    /**
     * @param array $data
     *
     * @return array
     */
    public function dump($data)
    {
        $return = [];
        foreach ($data as $t => $row) {
            $t = explode(DataCollectorInterface::DATA_SEPARATOR, $t);
            if ($row) {
                $values = array_map(function ($value) {
                    return $this->prepareValue($value);
                }, array_values($row));

                $table = end($t);
                $keys = implode(', ', array_keys($row));
                $values = implode(', ', $values);

                $return[] = sprintf('INSERT INTO %s (%s) VALUES (%s);', $table, $keys, $values);
            }
        }

        return $return;
    }
}
