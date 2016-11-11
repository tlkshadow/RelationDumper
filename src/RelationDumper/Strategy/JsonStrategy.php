<?php

namespace RelationDumper\Strategy;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
class JsonStrategy implements StrategyInterface
{
    /**
     * @link http://php.net/manual/en/function.json-encode.php For more information about $options
     *
     * @var int
     */
    private $options = 0;

    /**
     * @link http://php.net/manual/en/function.json-encode.php For more information about $depth
     *
     * @var int
     */
    private $depth = 512;

    public function dump($data)
    {
        return json_encode($data, $this->options, $this->depth);
    }
}
