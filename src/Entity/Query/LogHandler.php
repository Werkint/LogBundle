<?php
namespace Werkint\Bundle\LogBundle\Entity\Query;

use Doctrine\ORM\QueryBuilder;
use Werkint\Bundle\LogBundle\Entity\LogRepository;
use Werkint\Bundle\QueryBundle\Service\Handler\AbstractDoctrineHandler;
use Werkint\Bundle\QueryBundle\Service\Query\QueryInterface;

/**
 * @see    LogQuery
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class LogHandler extends AbstractDoctrineHandler
{
    private $repository;

    public function __construct(
        LogRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @inheritdoc
     */
    protected function applyFilters(QueryBuilder $qbr, QueryInterface $query)
    {
        if (!$query instanceof LogQuery) {
            throw new \Exception('Not supported');
        }

        if ($query->getObject()) {
            $qbr->andWhere(sprintf('%s.objectId = :objectId', static::TABLE_ALIAS))
                ->setParameter('objectId', $query->getObject()->getId())
                ->andWhere(sprintf('%s.objectClass = :objectClass', static::TABLE_ALIAS))
                ->setParameter('objectClass', get_class($query->getObject()));
        } else {
            $qbr->andWhere(sprintf('%s.objectId is null', static::TABLE_ALIAS))
                ->andWhere(sprintf('%s.objectClass is null', static::TABLE_ALIAS));
        }
    }

    /**
     * @inheritdoc
     */
    public function isQuerySupported(QueryInterface $query)
    {
        return $query instanceof LogQuery;
    }

    /**
     * @inheritdoc
     */
    protected function createQueryBuilder(QueryInterface $query)
    {
        return $this->repository->createQueryBuilder(static::TABLE_ALIAS)
            ->orderBy(sprintf('%s.loggedAt', static::TABLE_ALIAS), 'DESC')
            ->addOrderBy(sprintf('%s.id', static::TABLE_ALIAS), 'DESC');
    }
}
