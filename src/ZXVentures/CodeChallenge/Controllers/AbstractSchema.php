<?php

namespace ZXVentures\CodeChallenge\Controllers;

/**
 * Class AbstractValidator
 * @package ZXVentures\CodeChallenge\Controllers
 */
abstract class AbstractSchema
{
    /**
     * @param bool $asJson
     * @return \StdClass|string
     */
    public static function getSchema($asJson = false)
    {
        $instance = new static();

        return $asJson ? json_encode((array)$instance->_getSchema()) : $instance->_getSchema();
    }

    /**
     * @return \StdClass
     */
    public abstract function _getSchema();
}
