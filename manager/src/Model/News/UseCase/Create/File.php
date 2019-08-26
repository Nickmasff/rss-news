<?php

declare(strict_types=1);

namespace App\Model\News\UseCase\Create;

class File
{
    public $path;
    public $name;
    public $size;

    public function __construct(string $path, string $name, int $size)
    {
        $this->path = $path;
        $this->name = $name;
        $this->size = $size;
    }
}
