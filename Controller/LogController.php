<?php
namespace Werkint\Bundle\LogBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use Emisser\Bundle\ProcessingBundle\Entity\Transaction;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Nelmio\ApiDocBundle\Annotation\ApiDoc as API;
use Symfony\Component\HttpFoundation\Request;
use Werkint\Bundle\LogBundle\Entity\LogRepository;

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

    // -- Accessors ---------------------------------------

    /**
     * @API(description="Лог финансовых запросов"
     * , output="array<Werkint\Bundle\LogBundle\Entity\Log>"
     * )
     * @PreAuthorize("hasRole('ROLE_ADMIN')")
     * @Rest\Get("/list/{transaction}_all.json", name="emisser_processing_log_list"
     * , defaults={"_format": "json"}
     * )
     * @Rest\View()
     */
    public function listAction(Request $request, Transaction $transaction = null)
    {
        $updater = function (QueryBuilder $qb, $alias) use ($transaction) {
            if ($transaction) {
                $qb->andWhere($alias . '.objectId = :transaction')
                    ->setParameter('transaction', $transaction->getId());
            } else {
                $qb->andWhere($alias . '.objectId is null');
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
