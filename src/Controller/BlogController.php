<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findBy(array(), array('date' => 'DESC'));
        return $this->render('blog/home.html.twig', [
            "title" => "Bienvenue dans ce blog",
            "articles" => $articles,
        ]);
    }

    /**
     * @Route("/blog/new", name="create")
     * @Route("/blog/{id}/edit", name="edit")
     */
    public function form(Article $article = null, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$article->getId()) {
                $article->setDate(new \DateTime());
            }
            $manager->persist($article);
            $manager->flush();
            return $this->redirectToRoute('show', ['id' => $article->getId()]);
        }

        return $this->render('blog/create.html.twig', [
            "formArticle" => $form->createView(),
            "editing" => $article ? true : false
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
