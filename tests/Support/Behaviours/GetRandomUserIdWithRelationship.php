<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Support\Behaviours;

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

        return $qb->executeQuery()->fetchOne();
    }
}
