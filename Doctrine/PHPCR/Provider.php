<?php

namespace Fazland\ElasticaBundle\Doctrine\PHPCR;

use Doctrine\ODM\PHPCR\Query\Builder\QueryBuilder;
use Doctrine\ODM\PHPCR\Query\Query;
use Fazland\ElasticaBundle\Doctrine\AbstractProvider;
use Fazland\ElasticaBundle\Exception\InvalidArgumentTypeException;

class Provider extends AbstractProvider
{
    const ENTITY_ALIAS = 'a';

    /**
     * Disables logging and returns the logger that was previously set.
     *
     * @return mixed
     */
    protected function disableLogging()
    {
        return;
    }

    /**
     * Reenables the logger with the previously returned logger from disableLogging();
     *
     * @param mixed $logger
     * @return mixed
     */
    protected function enableLogging($logger)
    {
        return;
    }

    /**
     * {@inheritDoc}
     */
    protected function countObjects($queryBuilder)
    {
        if (! $queryBuilder instanceof QueryBuilder) {
            throw new InvalidArgumentTypeException($queryBuilder, 'Doctrine\ODM\PHPCR\Query\Builder\QueryBuilder');
        }

        return $queryBuilder
            ->getQuery()
            ->execute(null, Query::HYDRATE_PHPCR)
            ->getRows()
            ->count();
    }

    /**
     * {@inheritDoc}
     */
    protected function fetchSlice($queryBuilder, $limit, $offset)
    {
        if (! $queryBuilder instanceof QueryBuilder) {
            throw new InvalidArgumentTypeException($queryBuilder, 'Doctrine\ODM\PHPCR\Query\Builder\QueryBuilder');
        }

        return $queryBuilder
            ->getQuery()
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getResult()
            ->toArray();
    }

    /**
     * {@inheritDoc}
     */
    protected function createQueryBuilder($method, array $arguments=[])
    {
        return $this->managerRegistry
            ->getManager()
            ->getRepository($this->objectClass)
            ->{$method}(static::ENTITY_ALIAS);
    }
}
