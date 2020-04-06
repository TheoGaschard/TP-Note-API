<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleController extends AbstractController
{

    private $em;

    public function __construct( EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/article", name="article")
     */
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ArticleController.php',
        ]);

    }

    
    /**
     * @Route("/article", name="article_list", methods={"GET"})
     */
    public function list(ArticleRepository $articleRepository, SerializerInterface $serializer)
    {

        $articles = $articleRepository->findAll();

        return $this->json([
            'articles' => $articles
        ]);


    }

    /**
     * @Route("/article/{id}", name="article_detail", methods={"GET"})
     */
    public function detail(Article $article)
    {

        return $this->json([
            $article, 
            Response::HTTP_OK,
            [],
            []
        ]);
  
    }
    
    /**
     * @Route("/article", name="article_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $em)
    {
        $data = $request->getContent();
        $content = json_decode($data, true);

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article); // lie le formulaire à cet objet $article
        $form->submit($content);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($article);
            $em->flush();

            return $this->json(
                $article, 
                Response::HTTP_CREATED,
                [],
                []
            );

        }
        
        return $this->json([
            Response::HTTP_BAD_REQUEST
        ]);
        
        return new Response("ok");
  
    }

    /**
     * @Route("/article/{id}", name="delete_article", methods={"DELETE"})
     */
    public function delete(Article $article, EntityManagerInterface $em)
    {

        $em->remove($article);
        $em->flush();


        return $this->json('ok deleted');
  
    }



}
