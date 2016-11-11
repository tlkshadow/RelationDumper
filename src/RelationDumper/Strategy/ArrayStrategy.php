<?php

namespace RelationDumper\Strategy;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
class ArrayStrategy implements StrategyInterface
{
    public function dump($data)
    {
        return $data;
    }
}
