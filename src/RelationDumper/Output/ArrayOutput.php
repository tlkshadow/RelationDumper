<?php

namespace RelationDumper\Output;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
class ArrayOutput implements OutputInterface
{
    public function dump($data)
    {
        return $data;
    }
}
