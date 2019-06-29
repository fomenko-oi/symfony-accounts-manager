<?php

namespace App\Model\Category;

use Doctrine\DBAL\Connection;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CategoryFetcher
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
            ->select(
                'c.*',
                '(
                    SELECT COUNT(a.id) 
                    FROM accounts a
                    WHERE a.category_id = c.id
                ) as accounts_count'
            )
            ->from('categories', 'c')
            ->orderBy('c.id', 'desc')
        ;

        return $this->paginator->paginate($db, $page, $size);
    }
}
