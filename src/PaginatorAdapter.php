<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels;

use Pagerfanta\Adapter\AdapterInterface;
use function call_user_func;
use function sprintf;

/**
 * Class PaginatorAdapter
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\PaginatorAdapter
 */
final class PaginatorAdapter implements AdapterInterface
{

    private $builder;
    private $countBuilder;

    /**
     * Constructor.
     *
     * @param ModelBuilder $queryBuilder
     */
    public function __construct(ModelBuilder $queryBuilder)
    {
        $this->builder      = clone $queryBuilder;
        $this->countBuilder = function (ModelBuilder $query) {
            $qb = $query->getQueryBuilder();
            $qb
                ->select(sprintf('COUNT(DISTINCT %s) AS total_results', $query->getModel()->meta()->primaryKeyNameWithAlias()))
                ->setMaxResults(1)
                // @todo is this enough to work reliably?
                ->resetQueryPart('orderBy')
            ;
        };
    }

    /**
     * {@inheritdoc}
     */
    public function getNbResults()
    {
        return (int) $this->prepareCountQueryBuilder()->getQueryBuilder()->execute()->fetchColumn();
    }

    private function prepareCountQueryBuilder()
    {
        $qb = clone $this->builder;

        call_user_func($this->countBuilder, $qb);

        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        $qb = clone $this->builder;

        return $qb->limit($length)->offset($offset)->fetch();
    }
}
