<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels;

use Pagerfanta\Adapter\AdapterInterface;

/**
 * Class PaginatorAdapter
 *
 * @package    Somnambulist\ReadModels
 * @subpackage Somnambulist\ReadModels\PaginatorAdapter
 */
class PaginatorAdapter implements AdapterInterface
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
            $query
                ->getQueryBuilder()
                ->select(sprintf('COUNT(DISTINCT %s) AS total_results', $query->getModel()->getPrimaryKeyWithTableAlias()))
                ->setMaxResults(1)
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
