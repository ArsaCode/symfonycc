<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;


class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i = 0; $i <= 3; $i++) {
            $category = new Category();
            $category->setTitle($faker->sentence())
                    ->setDescription($faker->paragraph());
            $manager->persist($category);
            for($j = 1; $j <= 6; $j++) {
                $article = new Article();
                $article->setTitle($faker->sentence())
                        ->setContent(implode($faker->paragraphs(5)))
                        ->setImage($faker->imageUrl())
                        ->setDate($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);
                $manager->persist($article);
                for($k = 0; $k <= 5; $k++) {
                    $comment = new Comment();
                    $now = new \DateTime();
                    $interval = $now->diff($article->getDate());
                    $days = $interval->days;
                    $interval = '-' . $days . ' days';
                    $comment->setAuthor($faker->name())
                            ->setArticle($article)
                            ->setContent($faker->paragraph())
                            ->setDate($faker->dateTimeBetween($interval));
                    $manager->persist($comment);
                }
            }
        }
        $manager->flush();
    }
}
