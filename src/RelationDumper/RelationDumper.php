<?php

namespace RelationDumper;

use RelationDumper\DataCollector\DataCollectorInterface;
use RelationDumper\Strategy\StrategyInterface;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
class RelationDumper
{
    /**
     * @var StrategyInterface
     */
    private $strategy;
    
    /**
     * @var DataCollectorInterface
     */
    private $dataCollector;

    /**
     * @param DataCollectorInterface $dataCollector
     * @param StrategyInterface $strategy
     */
    public function __construct(DataCollectorInterface $dataCollector, StrategyInterface $strategy)
    {
        $this->dataCollector = $dataCollector;
        $this->strategy = $strategy;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return $this->strategy->dump(
            $this->dataCollector->getData()
        );
    }
}
