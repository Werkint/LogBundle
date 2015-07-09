<?php
namespace Werkint\Bundle\LogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Obmenat\Bundle\AppBundle\Service\PageableTrait;

/**
 * @see    Log
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class LogRepository extends EntityRepository
{
    use PageableTrait;
}
