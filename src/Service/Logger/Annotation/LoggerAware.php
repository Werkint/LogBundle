<?php
namespace Werkint\Bundle\LogBundle\Service\Logger\Annotation;

use Doctrine\ORM\Mapping\Annotation;

/**
 * Отмечает метод, для которого нужен логгер
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 *
 * @Annotation
 * @Target("METHOD")
 */
class LoggerAware
{
    protected $parameter;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->parameter = isset($data['parameter']) ? $data['parameter'] : null;
    }

    // -- Accessors ---------------------------------------

    /**
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }
} 