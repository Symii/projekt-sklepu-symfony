<?php
namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = [];

        foreach (['Peripherals', 'Graphics Cards', 'Processors', 'Monitors', 'Mice', 'Keyboards', 'Headphones'] as $name) {
            $category = new Category();
            $category->setName($name);
            $category->setDescription("Description for $name");
            $manager->persist($category);
            $categories[] = $category;
        }

        $product = new Product();
        $product->setName('Example Product');
        $product->setDescription('Description of example product');
        $product->setPrice(99.99);
        $product->setStock(100);
        $product->setCategory($categories[0]); // Assign to "Peripherals"
        $manager->persist($product);

        $manager->flush();
    }
}