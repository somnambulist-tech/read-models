<?php declare(strict_types=1);

namespace Somnambulist\Components\ReadModels\Tests\Support\Behaviours;

trait GetRandomUserIdByCriteria
{

    /**
     * Criteria is an array of: [column, comparison, value]
     *
     * For example: where name like bob would be:
     * $criteria = ['name', 'like', '%bob%',],
     *
     * Does not work for nulls etc. Just for selecting records for testing.
     *
     * @param array $criteria
     *
     * @return false|mixed
     */
    protected function getRandomUserIdWhere(array $criteria)
    {
        // hacky...
        global $connection;

        $qb = $connection->createQueryBuilder();
        $qb
            ->select('id')->from('users')->orderBy('RANDOM()')->setMaxResults(1)
        ;

        foreach ($criteria as $test) {
            $qb->andWhere($qb->expr()->comparison($test[0], $test[1], $test[2]));
        }

        return $qb->execute()->fetchColumn();
    }
}
