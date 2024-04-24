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
     * @Route("/list", name="blog_list", defaults={"page": 6}, methods={"GET"}, requirements={"page": "\d+"})
     */
    public function list($page = 1, Request $request): JsonResponse
    {
        $repository = $this->em->getRepository(BlogPost::class);
        $items = $repository->findAll();
        dd($items); 

        return new JsonResponse([
            'page' => $page,
            'data' => $items
        ]);
    }
    // /**
    //  * @Route("/{id}", name="blog_by_id", methods={"GET"}, requirements={"id": "\d+"})
    //  */
    // public function post($id): JsonResponse
    // {
    //     dd(50);
    //     return new JsonResponse(
    //         self::POSTS[array_search($id, array_column(self::POSTS, 'id'))]
    //     );
    // }
    // /**
    //  * @Route("/{slug}", name="blog_by_slug", methods={"GET"})
    //  */
    // public function postBySlug($slug): JsonResponse
    // {
    //     return new JsonResponse(
    //         self::POSTS[array_search($slug, array_column(self::POSTS, 'slug'))]
    //     );
    // }
    
    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer)
    {
        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);  
        $em->flush();   // Radi insert query

        return $this->json($blogPost);
    }
}
