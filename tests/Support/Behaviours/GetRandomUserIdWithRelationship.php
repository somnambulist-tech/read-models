<?php declare(strict_types=1);

namespace Somnambulist\ReadModels\Tests\Support\Behaviours;

/**
 * Trait GetRandomUserIdWithRelationship
 *
 * @package    Somnambulist\ReadModels\Tests\Support\Behaviours
 * @subpackage Somnambulist\ReadModels\Tests\Support\Behaviours\GetRandomUserIdWithRelationship
 */
trait GetRandomUserIdWithRelationship
{

    /**
     * Get a User id that has an entry in the table via an inner join
     *
     * @param string $table
     * @param string $alias
     * @param string $condition
     *
     * @return false|mixed
     */
    protected function getRandomUserIdWithRelationship($table, $alias, $condition)
    {
        // hacky...
        global $connection;

        $qb = $connection->createQueryBuilder();
        $qb
            ->select('u.id')
            ->from('users', 'u')
            ->innerJoin('u', $table, $alias, $condition)
            ->orderBy('RANDOM()')
            ->setMaxResults(1)
        ;

        return $qb->execute()->fetchColumn();
    }
}
