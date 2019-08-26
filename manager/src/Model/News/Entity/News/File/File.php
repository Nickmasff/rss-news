<?php

declare(strict_types=1);

namespace App\Model\News\Entity\News\File;

use App\Model\News\Entity\News\News;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="news_files", indexes={
 *     @ORM\Index(columns={"date"})
 * })
 */
class File
{
    /**
     * @var  News
     * @ORM\ManyToOne(targetEntity="App\Model\News\Entity\News\News", inversedBy="files")
     * @ORM\JoinColumn(name="mews_id", referencedColumnName="id", nullable=false)
     */
    private $news;
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\Id
     */
    private $id;
    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;
    /**
     * @var Info
     * @ORM\Embedded(class="Info")
     */
    private $info;

    public function __construct(News $news, \DateTimeImmutable $date, Info $info)
    {
        $this->news = $news;
        $this->date = $date;
        $this->info = $info;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getInfo(): Info
    {
        return $this->info;
    }
}
