<?php

/*
 * @package Symfony Framework
 *
 * @author App bloggy <robertdequidt@gmail.com>
 *
 * @copyright 2022-2023
 */

namespace App\Controller\Frontend;

use App\Form\SearchedType;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogSearchedController extends AbstractController
{
    public function __construct(private readonly PaginatorInterface $paginator)
    {
    }

    #[Route('/search', name: 'blog_search', methods: [Request::METHOD_GET])]
    public function blogSearched(Request $request): Response
    {
        return $this->render('frontend/post/blog-searched.html.twig', ['query' => (string) $request->query->get('q', '')]);
    }

    #[Route('/searched', name: 'blog_searched', methods: [Request::METHOD_GET])]
    public function searched(Request $request, PostRepository $postRepository): Response
    {
        $searchedForm = $this->createForm(SearchedType::class, null, [
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
        $searchedQuery = $request->query->get('q');
        $results = [];
        $searchedForm->handleRequest($request);

        if ($searchedForm->isSubmitted() && $searchedForm->isValid()) {
            $searchedQuery = $searchedForm['q']->getData();
            $results = $postRepository->searched($searchedQuery);
        }

        return $this->render('frontend/post/searched/blog-searched.html.twig', compact('searchedQuery', 'searchedForm', 'results'));
    }

    /*
    public function blogsearched(Request $request, PostRepository $postRepository): Response
    {
        $searchedForm = $this->createForm(SearchedType::class, null, [
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
        $searchedQuery = $request->query->get('q');
        $searchedForm->handleRequest($request);

        if ($searchedForm->isSubmitted() && $searchedForm->isValid()) {
            $results = $postRepository->searched($searchedQuery);
        }

        return $this->render('post/searched/blog-searched.html.twig', [
            'searchedQuery' => $searchedQuery,
            'searchedForm' => $searchedForm,
            'results' => $results ?? [],
        ]);
    }
     */

    /*
    public function blogMeiliSearched(Request $request, SearchService $searchService): Response
    {
        $searchedForm = $this->createForm(SearchedType::class, null, [
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
        $searchedQuery = $request->query->get('q');
        $searchedForm->handleRequest($request);

        if ($searchedForm->isSubmitted() && $searchedForm->isValid()) {
            $searchedResponse = $searchService->rawSearch(Post::class, $searchedQuery, [
                'attributesToHighlight' => ['title', 'content', 'excerpt'],
                'highlightPreTag' => '<mark>',
                'highlightPostTag' => '</mark>',
                'attributesToCrop' => ['content'],
                'cropLength' => 20,
            ]);
            $results = $searchedResponse['hits'];
        }

        return $this->render('post/searched/blog-meili-searched.html.twig', [
            'searchedQuery' => $searchedQuery,
            'searchedForm' => $searchedForm,
            'tableau' => ['nom' => 'Parlons Code', 'age' => 6],
            'results' => $results ?? [],
        ]);
    }
    */
}
