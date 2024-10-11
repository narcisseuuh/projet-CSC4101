<?php

namespace App\DataFixtures;

use App\Entity\Panier;
use App\Entity\Fruit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    /**
     * Generates initialization data for fruits : [name, color, quantity]
     * @return \\Generator
     */
    private static function fruitsDataGenerator1()
    {
            yield ["Pomme", "Rouge", 10];
            yield ["Banane", "Jaune", 20];
    }

    private static function fruitsDataGenerator2()
    {
            yield ["Poire", "Vert", 2];
            yield ["Kiwi", "Vert", 1000];
    }
    
    public function load(ObjectManager $manager): void
    {
        $panier = new Panier();
        $panier->setNom("Panier de Narcisse");

        foreach (self::fruitsDataGenerator1() as [$name, $color, $amount]) {
            $fruit = new Fruit();
            $fruit->setNom($name);
            $fruit->setCouleur($color);
            $fruit->setQuantite($amount);
            $panier = $panier->addFruit($fruit);
            $manager->persist($fruit);
        }
        $manager->persist($panier);

        $panier2 = new Panier();
        $panier2->setNom("Panier de Lowen");

        foreach (self::fruitsDataGenerator2() as [$name, $color, $amount]) {
            $fruit = new Fruit();
            $fruit->setNom($name);
            $fruit->setCouleur($color);
            $fruit->setQuantite($amount);
            $panier2 = $panier2->addFruit($fruit);
            $manager->persist($fruit);
        }
        $manager->persist($panier2);

        $manager->flush();
    }
}
