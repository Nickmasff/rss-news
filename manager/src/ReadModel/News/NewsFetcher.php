<?php

declare(strict_types=1);

namespace App\ReadModel\News;

use App\Model\News\Entity\News\News;
use App\ReadModel\News\Filter\Filter;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class NewsFetcher
{
    private $connection;
    private $paginator;
    private $repository;

    public function __construct(Connection $connection, EntityManagerInterface $em, PaginatorInterface $paginator)
    {
        $this->connection = $connection;
        $this->repository = $em->getRepository(News::class);
        $this->paginator = $paginator;
    }

    public function find(string $id): ?News
    {
        return $this->repository->find($id);
    }

    /**
     * @param Filter $filter
     * @param int $page
     * @param int $size
     * @param string $sort
     * @param string $direction
     * @return PaginationInterface
     */
    public function all(Filter $filter, int $page, int $size, string $sort, string $direction): PaginationInterface
    {
        $qb = $this->connection->createQueryBuilder()
            ->select(
                'n.id',
                'n.content_title',
                'n.content_description',
                'n.date',
                's.name'
            )
            ->from('news', 'n')
            ->leftJoin('n', 'sources', 's', 's.id = n.source_id')
        ;

        if ($filter->title) {
            $qb->andWhere($qb->expr()->like('LOWER(n.content_title)', ':title'));
            $qb->setParameter(':title', '%' . mb_strtolower($filter->title) . '%');
        }

        if (!\in_array($sort, ['date', 'content_title'], true)) {
            throw new \UnexpectedValueException('Cannot sort by ' . $sort);
        }

        $qb->orderBy($sort, $direction === 'desc' ? 'desc' : 'asc');

        return $this->paginator->paginate($qb, $page, $size);
    }

    public function exists(string $id): bool
    {
        return $this->connection->createQueryBuilder()
                ->select('COUNT (id)')
                ->from('work_members_members')
                ->where('id = :id')
                ->setParameter(':id', $id)
                ->execute()->fetchColumn() > 0;
    }

}
