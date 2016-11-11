<?php

namespace RelationDumper;

use RelationDumper\DataCollector\DataCollectorInterface;
use RelationDumper\Output\OutputInterface;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
class RelationDumper
{
    /**
     * @var OutputInterface
     */
    private $output;
    
    /**
     * @var DataCollectorInterface
     */
    private $dataCollector;

    /**
     * @param DataCollectorInterface $dataCollector
     * @param OutputInterface $output
     */
    public function __construct(DataCollectorInterface $dataCollector, OutputInterface $output)
    {
        $this->dataCollector = $dataCollector;
        $this->output = $output;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        return $this->output->dump(
            $this->dataCollector->getData()
        );
    }
}
