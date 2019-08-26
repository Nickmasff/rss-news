<?php

declare(strict_types=1);

namespace App\Model\News\UseCase\Create;

use App\Model\Flusher;
use App\Model\News\Entity\News\Content;
use App\Model\News\Entity\News\File\Info;
use App\Model\News\Entity\News\Image;
use App\Model\News\Entity\News\News;
use App\Model\News\Entity\News\NewsRepository;
use App\Model\News\Entity\News\SourceRepository;
use App\Service\Uploader\FileUploader;


class Handler
{
    private $flusher;

    private $newsRepository;

    private $fileUploader;

    private $sourceRepository;

    public function __construct(NewsRepository $newsRepository, SourceRepository $sourceRepository, FileUploader $fileUploader, Flusher $flusher)
    {
        $this->flusher = $flusher;
        $this->newsRepository = $newsRepository;
        $this->fileUploader = $fileUploader;
        $this->sourceRepository = $sourceRepository;
    }

    public function handle(Command $command): void
    {
        if (!$this->newsRepository->hasByEid($command->eid)) {

            $source = $this->sourceRepository->get($command->source);

            $news = News::createFromSource(
                new \DateTime(),
                new Content($command->title, $command->description),
                $command->pubDate,
                $source,
                $command->url,
                $command->eid);

            foreach ($command->images as $image) {
                $news->addFile(new \DateTimeImmutable(), new Info(
                    $image->path,
                    $image->name,
                    $image->size
                ));
            }

            $this->newsRepository->add($news);

            $this->flusher->flush();
        } else {
            echo 'News with eid ' . $command->eid . ' exists';
        }

    }
}

