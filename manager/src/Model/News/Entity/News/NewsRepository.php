<?php

declare(strict_types=1);

namespace App\Model\News\Entity\News;


use App\Model\User\Entity\User\Email;
use Doctrine\ORM\EntityManagerInterface;

class NewsRepository
{
    private $em;
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(News::class);
    }

    public function hasByEid(string $eid): bool
    {
        return $this->repo->createQueryBuilder('n')
                ->select('COUNT(n.id)')
                ->andWhere('n.eid = :eid')
                ->setParameter(':eid', $eid)
                ->getQuery()->getSingleScalarResult() > 0;
    }

    public function findLastBySource(int $id): ?News
    {
      return $this->repo->createQueryBuilder('n')
           ->andWhere('n.source = :source')
           ->setParameter(':source', $id)
           ->orderBy('n.datePub', 'DESC')
           ->setMaxResults(1)
           ->getQuery()->getOneOrNullResult();
    }

    public function add(News $news): void
    {
        $this->em->persist($news);
    }
}
