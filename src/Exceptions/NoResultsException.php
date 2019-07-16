<?php

declare(strict_types=1);

namespace Somnambulist\ReadModels\Exceptions;

use Doctrine\DBAL\Query\QueryBuilder;
use Exception;

/**
 * Class NoResultsException
 *
 * @package    Somnambulist\ReadModels\Exceptions
 * @subpackage Somnambulist\ReadModels\Exceptions\NoResultsException
 */
class NoResultsException extends Exception
{

    /**
     * @var QueryBuilder
     */
    private $query;

    /**
     * @param string       $class
     * @param QueryBuilder $queryBuilder
     *
     * @return NoResultsException
     */
    public static function noResultsForQuery(string $class, QueryBuilder $queryBuilder): self
    {
        $exception = new self(sprintf('Could not match any records for %s', $class));
        $exception->query = clone $queryBuilder;

        return $exception;
    }

    /**
     * @return QueryBuilder
     */
    public function getQuery(): QueryBuilder
    {
        return $this->query;
    }
}
