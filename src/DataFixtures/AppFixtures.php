<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Car;
use App\Entity\CarCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $list=[];
        $number=5;
        $faker = Factory::create();
        for($i=0;$i<$number;$i++){
            $category=new CarCategory();
            $category->setName($faker->name());
            $list[]=$category;
            $manager->persist($category);
        }
        for($j=0;$j<30;$j++){
            $car=new Car();
            $car->setName($faker->name());
            $car->setNbDoors($faker->randomDigit());
            $car->setNbSeats($faker->randomDigit());
            $car->setCost($faker->randomFloat());
            $car->setCategory($list[rand(1,$number)-1]);
            $manager->persist($car);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
