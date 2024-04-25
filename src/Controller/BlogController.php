<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/index", name="blog_index", defaults={"page": 6}, methods={"GET"}, requirements={"page": "\d+"})
     */
    public function index($page = 1, Request $request): JsonResponse
    {
        $repository = $this->em->getRepository(BlogPost::class);
        $items = $repository->findAll();

        return new JsonResponse([
            'page' => $page,
            'data' => array_map(function ($item) {
                return [
                    'id' => $item->getId(),
                    'title' => $item->getTitle(),
                    'published' => $item->getPublished(),
                    'content' => $item->getContent(),
                    'author' => $item->getAuthor(),
                    'slug' => $item->getSlug()
                ];
            }, $items)
        ]);
    }
    /**
     * @Route("/show/{id}", name="blog_show_id", methods={"GET"})
     */
    public function show($id): JsonResponse
    {
        $repository = $this->em->getRepository(BlogPost::class);
        $item = $repository->find($id);
        if (!$item) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }

        $responseData = [
            'id' => $item->getId(),
            'title' => $item->getTitle(),
            'published' => $item->getPublished(),
            'content' => $item->getContent(),
            'author' => $item->getAuthor(),
            'slug' => $item->getSlug(),
        ];

        return new JsonResponse([
            'data' => $responseData
        ]);
    }

    /**
     * @Route("/show-by-slug/{slug}", name="blog_show_by_slug", methods={"GET"})
     */
    public function showBySlug($slug): JsonResponse
    {
        $repository = $this->em->getRepository(BlogPost::class);
        $item = $repository->findOneBy(['slug' => $slug]);
        if (!$item) {
            return new JsonResponse(['error' => 'Not found'], 404);
        }

        $responseData = [
            'id' => $item->getId(),
            'title' => $item->getTitle(),
            'published' => $item->getPublished(),
            'content' => $item->getContent(),
            'author' => $item->getAuthor(),
            'slug' => $item->getSlug(),
        ];

        return new JsonResponse([
            'data' => $responseData
        ]);
    }   
    
    /**
     * @Route("/store", name="blog_store", methods={"POST"})
     */
    public function store(Request $request, SerializerInterface $serializer)
    {
        $data = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');
        $this->em->persist($data);  
        $this->em->flush();   // Radi insert query
        return $this->json($data);
    }

    /**
     * @Route("/delete/{id}", name="blog_delete", methods={"DELETE"})
     */
    public function delete($id): JsonResponse
    {
        $repository = $this->em->getRepository(BlogPost::class);
        $item = $repository->find($id);
        $this->em->remove($item);
        $this->em->flush();

        return new JsonResponse([
            'message' => 'Deleted',
        ], 200);
    }
}
