<?php

namespace App\Model\Category\Account;

use Doctrine\DBAL\Connection;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class AccountFetcher
{
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    public function __construct(Connection $connection, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->paginator = $paginator;
    }

    public function all(int $page, int $size): PaginationInterface
    {
        $db = $this->connection->createQueryBuilder()
            ->select('a.*')
            ->from('accounts', 'a')
            ->leftJoin('a', 'categories', 'category', 'a.category_id = category.id')
            ->orderBy('a.id', 'desc')
        ;

        return $this->paginator->paginate($db, $page, $size);
    }

    public function forCategory(int $categoryId, int $page, int $size): PaginationInterface
    {
        $db = $this->connection->createQueryBuilder()
            ->select('a.*')
            ->from('accounts', 'a')
            ->andWhere('a.category_id = :category_id')
            ->setParameter(':category_id', $categoryId)
            ->leftJoin('a', 'categories', 'category', 'a.category_id = category.id')
            ->orderBy('a.id', 'desc')
        ;

        return $this->paginator->paginate($db, $page, $size);
    }
}
