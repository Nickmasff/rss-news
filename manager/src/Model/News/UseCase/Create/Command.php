<?php

declare(strict_types=1);

namespace App\Model\News\UseCase\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $title;
    /**
     * @var string
     */
    public $description;
    /**
     * @var \DateTime
     * @Assert\DateTime()
     */
    public $pubDate;
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $url;
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $eid;
    /**
     * @var int
     * @Assert\NotBlank()
     */
    public $source;
    /**
     * @var []File
     * @Assert\Type("array")
     */
    public $images;

    public function __construct(string $title, $description)
    {
        $this->title = $title;
        $this->description = $description;
    }

    public static function fromSource(string $title, $description, string $eid, \DateTime $pubDate, string $url, int $source, array $images): self
    {
        $command = new self($title, $description);
        $command->eid = $eid;
        $command->pubDate = $pubDate;
        $command->url = $url;
        $command->source = $source;
        $command->images = $images;
        return $command;
    }

}
