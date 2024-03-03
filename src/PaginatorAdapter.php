<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels;

use Doctrine\DBAL\Query\QueryBuilder;
use Pagerfanta\Adapter\AdapterInterface;
use Somnambulist\Components\ReadModels\Utils\ClassHelpers;
use function sprintf;

final class PaginatorAdapter implements AdapterInterface
{
    private ModelBuilder $builder;

    public function __construct(ModelBuilder $queryBuilder)
    {
        $this->builder = clone $queryBuilder;
    }

    private function prepareCountQueryBuilder(): QueryBuilder
    {
        $query = clone $this->builder;

        $qb = $query->getQueryBuilder();
        $qb->resetOrderBy();

        if (0 === ClassHelpers::countPart($qb, 'select')) {
            $qb->select($query->model->meta()->primaryKeyNameWithAlias());
        }
        if (ClassHelpers::countPart($qb, 'groupBy') > 0) {
            $qb->select(...ClassHelpers::get($qb, 'groupBy'));
        }

        $counter = new QueryBuilder(Manager::instance()->connect($this->builder->getModel()));
        $counter
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
        $qb = $this->prepareCountQueryBuilder();

        return (int)$qb->executeQuery()->fetchOne();
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
