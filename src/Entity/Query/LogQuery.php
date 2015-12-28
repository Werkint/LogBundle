<?php
namespace Werkint\Bundle\LogBundle\Entity\Query;

use JMS\Serializer\Annotation as Serializer;
use Werkint\Bundle\LogBundle\Service\Logger\LoggableObjectInterface;
use Werkint\Bundle\QueryBundle\Service\Query\GenericQuery;

/**
 * Объект запроса к логам
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class LogQuery extends GenericQuery
{
    /**
     * Только админы
     *
     * @var LoggableObjectInterface|null
     * @Serializer\Type("Werkint\Bundle\LogBundle\Service\Logger\LoggableObjectInterface")
     * @Serializer\Groups("query")
     */
    private $object;

    public function clear()
    {
        parent::clear();

        $this
            ->setObject(null);
    }

    // -- Accessors ---------------------------------------

    /**
     * @return null|LoggableObjectInterface
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param null|LoggableObjectInterface $object
     * @return $this
     */
    public function setObject(LoggableObjectInterface $object = null)
    {
        $this->object = $object;
        return $this;
    }
}
