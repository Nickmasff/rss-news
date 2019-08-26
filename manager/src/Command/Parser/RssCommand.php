<?php
declare(strict_types=1);

namespace App\Command\Parser;

use App\Model\News\Entity\News\NewsRepository;
use App\Model\News\Entity\News\SourceRepository;
use App\Service\Uploader\FileUploader;
use FeedIo\Feed\Item\MediaInterface;
use FeedIo\FeedIo as RssReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Model\News\UseCase\Create;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RssCommand extends Command
{
    private $feedIo;

    private $validator;

    private $handler;

    private $sourceRepository;

    private $newsRepository;
    
    private $uploader;

    public function __construct(RssReader $feedIo, ValidatorInterface $validator, Create\Handler $handler, SourceRepository $sourceRepository, NewsRepository $newsRepository, FileUploader $uploader)
    {
        $this->feedIo = $feedIo;
        $this->validator = $validator;
        $this->handler = $handler;
        $this->sourceRepository = $sourceRepository;
        $this->newsRepository = $newsRepository;
        $this->uploader = $uploader;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('parser:rss')
            ->setDescription('Start rss parsing');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sources = $this->sourceRepository->findAvailableSources();

        foreach ($sources as $source) {

            $last = $this->newsRepository->findLastBySource($source->getId());
            $modifiedSince = $last ? $last->getDatePub() : new \DateTime('-1 hour');

            $feed = $this->feedIo->readSince($source->getRssUrl(), $modifiedSince)->getFeed();


            $output->writeln('<info>' . $source->getName() . ': ' . $source->getRssUrl() . ', ' . count($feed) . ' items since ' . $modifiedSince->format('Y:m:d H-i-s') .  '</info>');

            foreach ($feed as $feedItem ) {

                $files = [];
                foreach ($feedItem->getMedias() as $media) {
                    /**
                     * @var $media MediaInterface
                     */
                    $uploaded = $this->uploader->upload($file = $this->uploader->makeUploadedFile($media->getUrl()));

                    $files[] = new Create\File(
                        $uploaded->getPath(),
                        $uploaded->getName(),
                        $uploaded->getSize()
                    );
                }
                
                $command = Create\Command::fromSource($feedItem->getTitle(),  $feedItem->getDescription(), $feedItem->getPublicId(), $feedItem->getLastModified(), $feedItem->getLink(), $source->getId(), $files);

                $violations = $this->validator->validate($command);

                if ($violations->count()) {
                    foreach ($violations as $violation) {
                        $output->writeln('<error>' . $violation->getPropertyPath() . ': ' . $violation->getMessage() . '</error>');
                    }
                    return;
                }

                $this->handler->handle($command);
            }

        }



        $output->writeln('<info>Done!</info>');
    }
}
