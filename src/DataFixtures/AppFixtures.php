<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         /**
          * Run these commands:
          * php bin/console doctrine:fixtures:load
          * symfony console doctrine:fixtures:load
          */

        $blogPost = new BlogPost();  // Entity == Laravel Model
        // Use setters to set private properties
        $blogPost->setTitle('Hello World');
        $blogPost->setPublished(new \DateTimeImmutable());
        $blogPost->setContent('This is my first blog post!');
        $blogPost->setAuthor('John Doe');
        $blogPost->setSlug('hello-world');
        $manager->persist($blogPost);

        $blogPost2 = new BlogPost();
        $blogPost2->setTitle('Hello World 2');
        $blogPost2->setPublished(new \DateTimeImmutable());
        $blogPost2->setContent('This is my first blog post! 2222');
        $blogPost2->setAuthor('John Doe 222');
        $blogPost2->setSlug('hello-world-222');
        $manager->persist($blogPost2);

        $manager->flush();       
    }
}
