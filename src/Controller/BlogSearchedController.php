<?php

namespace App\Controller;

use App\Form\SearchedType;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogSearchedController extends AbstractController
{
    #[Route('/search', name: 'blog_search', methods: [Request::METHOD_GET])]
    public function blogSearched(Request $request): Response
    {
        return $this->render('post/blog-searched.html.twig', ['query' => (string) $request->query->get('q', '')]);
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

        return $this->render('post/searched/blog-searched.html.twig', compact('searchedQuery', 'searchedForm', 'results'));
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
