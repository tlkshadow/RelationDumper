<?php

namespace RelationDumper\Strategy\MySQL;

use RelationDumper\Strategy\StrategyInterface;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
class InsertStrategy implements StrategyInterface
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
