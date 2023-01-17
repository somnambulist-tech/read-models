<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels;

use Pagerfanta\Adapter\AdapterInterface;
use function count;
use function method_exists;
use function sprintf;

final class PaginatorAdapter implements AdapterInterface
{
    private ModelBuilder $builder;

    public function __construct(ModelBuilder $queryBuilder)
    {
        $this->builder = clone $queryBuilder;
    }

    private function prepareCountQueryBuilder(): ModelBuilder
    {
        $query = clone $this->builder;

        $qb = $query->getQueryBuilder();
        $qb->resetQueryPart('orderBy');

        if (count($qb->getQueryPart('select')) == 0) {
            $qb->select($query->model->meta()->primaryKeyNameWithAlias());
        }
        if (count($qb->getQueryPart('groupBy')) > 0) {
            $qb->select($qb->getQueryPart('groupBy'));
        }

        $counter = $query->newQuery();
        $counter
            ->getQueryBuilder()
            ->resetQueryParts()
            ->select('COUNT(*) AS total_results')
            ->from(sprintf('(%s)', $qb->getSQL()), 't1')
            ->setParameters($qb->getParameters())
        ;

        return $counter;
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults(): int
    {
        $qb = $this->prepareCountQueryBuilder()->getQueryBuilder();

        if (method_exists($qb, 'executeQuery')) {
            return (int) $qb->executeQuery()->fetchOne();
        }

        return (int)$qb->execute()->fetchOne();
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length): iterable
    {
        $qb = clone $this->builder;

        return $qb->limit($length)->offset($offset)->fetch();
    }
}
