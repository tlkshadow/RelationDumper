<?php

namespace RelationDumper\Output\MySQL;

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
            $values = array_map(function ($value) {
                return $this->prepareValue($value);
            }, array_values($row));

            $return[] = sprintf('INSERT %s VALUES (%s);', $t, implode(', ', $values));
        }

        return $return;
    }
}
