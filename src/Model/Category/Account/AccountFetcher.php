<?php

namespace App\Model\Category\Account;

use App\Entity\Account\Account;
use App\Entity\Category\Category;
use App\Model\Category\Account\Filter\Filter;
use App\Repository\Category\CategoryRepository;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
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

    private $repository;

    public function __construct(Connection $connection, PaginatorInterface $paginator, EntityManagerInterface $em)
    {
        $this->connection = $connection;
        $this->paginator = $paginator;
        $this->repository = $em->getRepository(Account::class);
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

    public function forCategory(Filter $filter, Category $category, int $page, int $size): PaginationInterface
    {
        $db = $this->connection->createQueryBuilder();

        $db
            ->select('a.*', 'c.name as category_name', 'afv.value')
            ->from('accounts', 'a')
            ->andWhere('a.category_id = :category_id')
            ->setParameter(':category_id', $category->getId())
            ->innerJoin('a', 'account_fields_values', 'afv', 'a.id = afv.account_id')
            ->leftJoin('a', 'categories', 'c', 'a.category_id = c.id')
            ->orderBy('a.id', 'desc')
        ;

        if($filter->hasFields()) {
            foreach ($filter->getFields() as $fieldId => $value) {
                // TODO make this feature. With ES mb. I don't know yet.
            }
        }

        if($login = $filter->login) {
            $db->andWhere('a.login = :login');
            $db->setParameter(':login', $login);
        }

        if($password = $filter->password) {
            $db->andWhere('a.password = :password');
            $db->setParameter(':password', $password);
        }

        return $this->paginator->paginate($db, $page, $size);
    }
}
