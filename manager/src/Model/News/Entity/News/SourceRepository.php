<?php

declare(strict_types=1);

namespace App\Model\News\Entity\News;

use App\Model\EntityNotFoundException;
use Doctrine\ORM\EntityManagerInterface;

class SourceRepository
{
    private $em;
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Source::class);
    }

    public function get($id): Source
    {
        /** @var Source $source */
        if (!$source = $this->repo->find($id)) {
            throw new EntityNotFoundException('Source is not found.');
        }
        return $source;
    }

    /**
     * @return Source[]
     */
    public function findAvailableSources(): array
    {
        return $this->repo->createQueryBuilder('s')
            ->select('s')
            ->getQuery()
            ->getResult();

    }


    public function add(Source $source): void
    {
        $this->em->persist($source);
    }
}
