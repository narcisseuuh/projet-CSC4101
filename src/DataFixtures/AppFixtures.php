<?php

namespace App\DataFixtures;

use App\Entity\Panier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Cuisine;
use App\Entity\Fruit;
use App\Entity\Member;
use App\Repository\CuisineRepository;
use App\Repository\FruitRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class AppFixtures extends Fixture 
{
        /**
         * Creates a function to hash passwords
         */

        private UserPasswordHasherInterface $hasher;

        public function __construct(UserPasswordHasherInterface $hasher)
        {
            $this->hasher = $hasher;
        }


      
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

        /**
         * Generates initialization data for members :
         *  [email, plain text password, role]
         * @return \\Generator
         */
        private function membersGenerator()
        {
                yield ['augustin@localhost','testtest','ROLE_ADMIN'];
                yield ['chabane@localhost','adminadmin','ROLE_USER'];
                yield ['thomas@localhost','pwnpwn','ROLE_USER'];
        }

        

        public function load(ObjectManager $manager)
        {
  
                $cuisine = new Cuisine();
                $cuisine->setNom("Cuisine de Narcisse");
                $panier1 = new Panier();
                $panier1->setNom("Panier super cool");


                foreach (self::fruitsDataGenerator1() as [$nom, $couleur, $quantite] ) {
                        $fruit = new Fruit();
                        $fruit->setNom($nom);
                        $fruit->setCouleur($couleur);
                        $fruit->setQuantite($quantite);
                        $manager->persist($fruit);
                        $cuisine->addFruit($fruit);
                        $panier1->addFruit($fruit);
                }

                $cuisine_bis = new Cuisine();
                $cuisine_bis->setNom("Cuisine de Lowen & Voodoux");
                $panier2 = new Panier();
                $panier2->setNom("Panier méga cool");

                foreach (self::fruitsDataGenerator2() as [$name, $color, $quantite]) {
                    $fruit = new Fruit();
                    $fruit->setNom($name);
                    $fruit->setCouleur($color);
                    $fruit->setQuantite($quantite);
                    $manager->persist($fruit);
                    $cuisine_bis->addFruit($fruit);
                    $panier2 = $panier2->addFruit($fruit);
                }

                foreach ($this->membersGenerator() as [$email, $plainPassword,$role]) {
                        $user = new Member();
                        $password = $this->hasher->hashPassword($user, $plainPassword);
                        $user->setEmail($email);
                        $user->setPassword($password);
                        $roles = array();
                        $roles[] = $role;
                        $user->setRoles($roles);
                        $manager->persist($user);
                }

                $manager->persist($cuisine);
                $manager->persist($cuisine_bis);
                $manager->flush();

                $MemberRepo = $manager->getRepository(Member::class);
                $user1 = $MemberRepo->findOneBy(['email' => 'augustin@localhost']);
                $user2 = $MemberRepo->findOneBy(['email' => 'chabane@localhost']);
                $user3 = $MemberRepo->findOneBy(['email'=> 'thomas@localhost']);

                $panier1->setCreator($user1);
                $panier2->setCreator($user2);

                $manager->persist($panier2);
                $manager->persist($panier1);
                
                $cuisine->setMember($user1);
                $cuisine_bis->setMember($user2);
                $cuisine_bis->setMember($user3);

                $manager->flush();
        }


}
