<?php
namespace Werkint\Bundle\LogBundle\Service\Logger;

/**
 * Объект, к которому цепляем лог
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
interface LoggableObjectInterface
{
    /**
     * @return string
     */
    public function getId();
}