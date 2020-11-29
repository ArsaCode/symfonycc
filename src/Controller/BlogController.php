<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findAll();
        return $this->render('blog/home.html.twig', [
            "title" => "Bienvenue dans ce blog",
            "articles" => $articles,
        ]);
    }

    /**
     * @Route("/blog/new", name="create")
     */
    public function create()
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
                    ->add('title')
                    ->add('content')
                    ->add('image')
                    ->getForm();
        return $this->render('blog/create.html.twig', [
            "formArticle" => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/{id}", name="show")
     */
    public function show(Article $article)
    {
        return $this->render('blog/article.html.twig', [
            "article" => $article,
        ]);
    }
}
