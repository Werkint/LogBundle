<?php
namespace Werkint\Bundle\LogBundle\Service\Logger;

use Psr\Log\LoggerAwareInterface as BaseInterface;

/**
 * Интерфейс для классов, которые будут вести лог операции
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 *
 * @see    http://php-and-symfony.matthiasnoback.nl/2012/03/symfony2-creating-a-metadata-factory-for-processing-custom-annotations/
 * @see    http://ans0600.wordpress.com/2013/07/14/use-aop-in-symfony2-project-with-custom-annotations/
 */
interface LoggerAwareInterface extends BaseInterface
{
}