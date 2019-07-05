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

    private $queryBuilder;
    private $countQueryBuilderModifier;

    /**
     * Constructor.
     *
     * @param Builder $queryBuilder
     */
    public function __construct(Builder $queryBuilder)
    {
        $this->queryBuilder = clone $queryBuilder;
        $this->countQueryBuilderModifier = function (Builder $query) {
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
        $qb = clone $this->queryBuilder;
        call_user_func($this->countQueryBuilderModifier, $qb);

        return $qb;
    }

    /**
     * {@inheritdoc}
     */
    public function getSlice($offset, $length)
    {
        $qb = clone $this->queryBuilder;

        return $qb->limit($length)->offset($offset)->fetch();
    }
}
