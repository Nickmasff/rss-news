<?php

declare(strict_types=1);

namespace App\Model\News\Entity\News;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sources")
 */

class Source
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     * @ORM\Id
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $rssUrl;

    /**
     * @var News[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Model\News\Entity\News\News", mappedBy="source", orphanRemoval=true, cascade={"persist"})
     */
    private $news;

    public function __construct($name, $rssUrl)
    {
        $this->name = $name;
        $this->rssUrl = $rssUrl;
    }

    /**
     * @return string
     */
    public function getRssUrl(): string
    {
        return $this->rssUrl;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}