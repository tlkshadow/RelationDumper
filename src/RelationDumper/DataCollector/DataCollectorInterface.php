<?php

namespace RelationDumper\DataCollector;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
interface DataCollectorInterface
{
    const DATA_SEPARATOR = '/';

    /**
     * @return mixed
     */
    public function getData();
}
