<?php

declare(strict_types=1);

namespace App\Model\News\Entity\News;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * @ORM\Embeddable
 */

class Content
{
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;
    /**
     * @var string
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct(string $title,  $description)
    {
        Assert::notEmpty($title);

        $this->title = $this->prepare($title);
        $this->description = $description ? $this->prepare($description) : $description;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    private function prepare($string)
    {
        $string = str_replace("<![CDATA[","",$string);
        $string = str_replace("]]>","",$string);
        return trim($string);
    }
}