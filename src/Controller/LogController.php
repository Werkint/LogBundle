<?php
namespace Werkint\Bundle\LogBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Nelmio\ApiDocBundle\Annotation\ApiDoc as API;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Werkint\Bundle\LogBundle\Entity\LogRepository;
use Werkint\Bundle\LogBundle\Service\Logger\LoggableObjectInterface;

/**
 * @see    Log
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 *
 * @Rest\Route("/log")
 */
class LogController
{
    /**
     * @var LogRepository
     * @DI\Inject("werkint_log.repo.log")
     */
    private $repoLog;
    /**
     * @var EntityManagerInterface
     * @DI\Inject("doctrine.orm.entity_manager")
     */
    private $entityManager;

    // -- Accessors ---------------------------------------

    /**
     * @API(description="Выгрузка лога"
     * , output="array<Werkint\Bundle\LogBundle\Entity\Log>"
     * )
     * @PreAuthorize("hasRole('ROLE_ADMIN')") TODO: нормальная роль
     * @Rest\Get("/list/{objectClass}_{objectId}_all.json", name="werkint_log_list"
     * , defaults={"_format": "json"}
     * )
     * @Rest\Get("/list/__all.json"
     * , defaults={"_format": "json"}
     * )
     * @Rest\View()
     */
    public function listAction(Request $request, $objectClass = null, $objectId = null)
    {
        $object = null;
        if ($objectClass || $objectId) {
            if (!($objectClass && $objectId)) {
                throw new NotFoundHttpException();
            }

            $repo = $this->entityManager->getRepository($objectClass);
            $object = $repo->find($objectId);

            if (!$object) {
                throw new NotFoundHttpException();
            }

            if (!$object instanceof LoggableObjectInterface) {
                throw new \Exception('Неправильный объект');
            }
        }

        $updater = function (QueryBuilder $qb, $alias) use ($object) {
            if ($object) {
                $qb->andWhere($alias . '.objectId = :objectId')
                    ->setParameter('objectId', $object->getId())
                    ->andWhere($alias . '.objectClass = :objectClass')
                    ->setParameter('objectClass', get_class($object));
            } else {
                $qb->andWhere($alias . '.objectId is null')
                    ->andWhere($alias . '.objectClass is null');
            }

            $qb->orderBy($alias . '.loggedAt', 'DESC')
                ->addOrderBy($alias . '.id', 'DESC');
        };

        if ($request->get('_pageable')) {
            return $this->repoLog->findAllPageable(
                $request->get('page'),
                $request->get('per_page'),
                $updater
            );
        }

        $qb = $this->repoLog->createQueryBuilder('l');
        $updater($qb, 'l');
        return $qb->getQuery()->getResult();
    }
}
