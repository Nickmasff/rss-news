<?php

declare(strict_types=1);

namespace App\Controller\News;

use App\Model\News\Entity\News\News;
use App\ReadModel\News\Filter;
use App\ReadModel\News\NewsFetcher;
use App\Controller\ErrorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/news", name="news"))
 */
class NewsController extends AbstractController
{
    private const PER_PAGE = 50;

    private $errors;

    public function __construct(ErrorHandler $errors)
    {
        $this->errors = $errors;
    }

    /**
     * @Route("", name="")
     * @param Request $request
     * @param NewsFetcher $fetcher
     * @return Response
     */
    public function index(Request $request, NewsFetcher $fetcher): Response
    {
        $filter = new Filter\Filter();

        $form = $this->createForm(Filter\Form::class, $filter);
        $form->handleRequest($request);

        $pagination = $fetcher->all(
            $filter,
            $request->query->getInt('page', 1),
            self::PER_PAGE,
            $request->query->get('sort', 'date'),
            $request->query->get('direction', 'asc')
        );

        return $this->render('app/news/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name=".show")
     * @param News $news
     * @return Response
     */
    public function show(News $news): Response
    {

        return $this->render('app/news/show.html.twig', ['news' => $news]);
    }
}
