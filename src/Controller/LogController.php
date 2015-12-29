<?php
namespace Werkint\Bundle\LogBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\SecurityExtraBundle\Annotation\PreAuthorize;
use Nelmio\ApiDocBundle\Annotation\ApiDoc as API;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Werkint\Bundle\LogBundle\Entity\Log;
use Werkint\Bundle\LogBundle\Entity\Query\LogQuery;
use Werkint\Bundle\QueryBundle\Service\QueryProcessorInterface;

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
     * @var QueryProcessorInterface
     * @DI\Inject("werkint_query.queryprocessor")
     */
    private $queryProcessor;

    // -- Accessors ---------------------------------------

    /**
     * @API(description="Выгрузка лога"
     * , output="array<Werkint\Bundle\LogBundle\Entity\Log>"
     * )
     * @PreAuthorize("hasRole('ROLE_ADMIN')") TODO: voter
     * @ParamConverter("query", converter="werkint_query.query")
     * @Rest\Get("/q={query}/list.json", name="werkint_log.log_list"
     * , defaults={"_format": "json"}, requirements={"query"=".+"}
     * )
     * @Rest\View()
     */
    public function listAction(LogQuery $query)
    {
        return $this->queryProcessor->process($query);
    }

    /**
     * @API(description="Выгрузка записи из лога"
     * , output="Werkint\Bundle\LogBundle\Entity\Log"
     * )
     * @PreAuthorize("hasRole('ROLE_ADMIN')") TODO: voter
     * @Rest\Get("/get/{log}.json", name="werkint_log.log_view"
     * , defaults={"_format": "json"}
     * )
     * @Rest\View()
     */
    public function getAction(Log $log)
    {
        return $log;
    }
}
