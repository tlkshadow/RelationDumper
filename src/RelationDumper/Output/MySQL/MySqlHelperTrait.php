<?php

namespace RelationDumper\Output\MySQL;

/**
 * @author Marcel Domke <ma_domke@hotmail.com>
 */
trait MySqlHelperTrait
{
    /**
     * @param string $value
     *
     * @return string|int
     */
    private function prepareValue($value)
    {
        $isInt = !is_int($value) ? ctype_digit($value) : true;

        if (!$isInt) {
            $value = sprintf("'%s'", $value);
        }

        return $value;
    }
}