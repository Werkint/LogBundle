<?php
namespace Werkint\Bundle\LogBundle\Service\Logger\Metadata;

use Metadata\MethodMetadata as BaseMethodMetadata;

/**
 * TODO: write "MethodMetadata" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class MethodMetadata extends BaseMethodMetadata
{
    protected $argument;

    // -- Accessors ---------------------------------------

    /**
     * @return mixed
     */
    public function getArgument()
    {
        return $this->argument;
    }

    /**
     * @param mixed $argument
     * @return $this
     */
    public function setArgument($argument)
    {
        $this->argument = $argument;
        return $this;
    }
} 