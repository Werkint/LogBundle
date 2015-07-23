<?php
namespace Werkint\Bundle\LogBundle\Service\Logger\Context;

/**
 * TODO: write "AbstractData" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
abstract class AbstractData
{
    const CONTEXT_KEY = 'logger_context_data';

    abstract public function getType();

    abstract protected function getData();

    public function dump()
    {
        return [
            static::CONTEXT_KEY => true,
            'type'              => $this->getType(),
            'data'              => $this->getData(),
        ];
    }
}