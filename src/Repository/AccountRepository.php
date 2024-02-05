<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\Util\AccountSearchCriteria;
use App\Entity\Util\SearchSorting;
use App\Entity\Util\SortingOrder;
use App\Exceptions\AccountNotFound;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\Persistence\ManagerRegistry;
use LogicException;

/**
 * @extends ServiceEntityRepository<Account>
 *
 * @method Account|null find($id, $lockMode = null, $lockVersion = null)
 * @method Account|null findOneBy(array $criteria, array $orderBy = null)
 * @method Account[]    findAll()
 * @method Account[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Account::class);
    }

    public function findAccountOrFail(int $accountId): Account
    {
        $account = $this->find($accountId);

        if (!$account) {
            throw AccountNotFound::byId($accountId);
        }

        return $account;
    }

    public function findByCriteria(AccountSearchCriteria $criteria): array
    {
        $builder = $this->_em->createQueryBuilder();
        $builder->select(select: 'account')->from(from: Account::class, alias: 'account');
        if (!is_null($criteria->clientId)) {
            $builder->andWhere($builder->expr()->eq(x: 'account.client', y: ':clientId'));
            $builder->setParameter(key: 'clientId', value: $criteria->clientId, type: Types::INTEGER);
        }

        if (!is_null($criteria->sorting)) {
            $sortingFieldsMapping = [
                AccountSearchCriteria::FIELD_CREATED_AT => 'account.createdAt',
            ];
            $builder->orderBy($this->buildOrderBy($criteria->sorting, $sortingFieldsMapping));
        }
        if ($criteria->pagination?->limit > 0) {
            $builder->setMaxResults($criteria->pagination?->limit);
        }
        if ($criteria->pagination?->offset > 0) {
            $builder->setFirstResult($criteria->pagination?->offset);
        }
        return $builder->getQuery()->getResult();
    }

    public function add(Account $account): void
    {
        $this->_em->persist($account);
        $this->_em->flush();
    }

    public function update(Account $account): void
    {
        $this->_em->persist($account);
        $this->_em->flush();
    }

    public function beginTransaction(): void
    {
        $this->_em->getConnection()->beginTransaction();
    }

    public function rollback(): void
    {
        $this->_em->getConnection()->rollBack();
    }

    public function commit(): void
    {
        $this->_em->getConnection()->commit();
    }

    /**
     * @param SearchSorting $searchSorting
     * @param array&array<string, string> $fieldsMapping
     * @return OrderBy
     */
    protected function buildOrderBy(SearchSorting $searchSorting, array $fieldsMapping): OrderBy
    {
        $sortingField = $fieldsMapping[$searchSorting->field] ?? throw new LogicException(
            message: sprintf('Sorting field "%s" is not supported.', $searchSorting->field),
        );

        return match ($searchSorting->order) {
            SortingOrder::ASC => $this->_em->getExpressionBuilder()->asc($sortingField),
            SortingOrder::DESC => $this->_em->getExpressionBuilder()->desc($sortingField),
            default => throw new LogicException(
                message: sprintf('Sorting order "%s" is not supported.', $searchSorting->order),
            ),
        };
    }
}