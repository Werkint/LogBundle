<?php
namespace Werkint\Bundle\LogBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Werkint\Bundle\FrameworkExtraBundle\Service\Query\PageableTrait;

/**
 * @see    Log
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class LogRepository extends EntityRepository
{
    use PageableTrait;
}
