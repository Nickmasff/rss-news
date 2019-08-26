<?php

declare(strict_types=1);

namespace App\Model\News\Entity\News;

use App\Model\News\Entity\News\File;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="news", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"link"}),
 *     @ORM\UniqueConstraint(columns={"eid"})
 * })
 */
class News
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\Id
     */
    private $id;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $date;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="publicated_at")
     */
    private $datePub;
    /**
     * @var Content
     * @ORM\Embedded(class="Content")
     */
    private $content;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $link;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $eid;

    /**
     * @var File\File[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Model\News\Entity\News\File\File", mappedBy="news", orphanRemoval=true, cascade={"persist"})
     */
    private $files;

    /**
     * @var Source|null
     * @ORM\ManyToOne(targetEntity="Source", inversedBy="news")
     * @ORM\JoinColumn(name="source_id", referencedColumnName="id", nullable=true)
     */
    private $source;

    private function __construct(\DateTime $date, Content $content)
    {
        $this->date = $date;
        $this->content = $content;
        $this->files = new ArrayCollection();
    }

    public static function createFromSource(\DateTime $date, Content $content, \DateTime $datePub, Source $source, string $link, string $eid): self
    {
        $news = new self($date, $content);
        $news->link = $link;
        $news->eid = $eid;
        $news->datePub = $datePub;
        $news->setSource($source);
        return $news;
    }

    public function addFile(\DateTimeImmutable $date,  File\Info $info): void
    {
        $this->files->add(new File\File($this, $date, $info));
    }

    /**
     * @return File\File[]|ArrayCollection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @return Source|null
     */
    public function getSource(): ?Source
    {
        return $this->source;
    }

    /**
     * @param Source|null $source
     */
    public function setSource(?Source $source): void
    {
        $this->source = $source;
    }

    /**
     * @return Content
     */
    public function getContent(): Content
    {
        return $this->content;
    }

    /**
     * @return \DateTime
     */
    public function getDatePub(): \DateTime
    {
        return $this->datePub;
    }

    /**
     * @return string
     */
    public function getLink(): string
    {
        return $this->link;
    }


}
