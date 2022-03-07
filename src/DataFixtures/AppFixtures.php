<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\User;
use App\Entity\Vinyl;
use App\Repository\CategorieRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Faker\Factory::create('fr_FR');
        //$categorieRepository = new CategorieRepository();  
        //$cat = $categorieRepository->findOneBy(['id'=>1]);
        for ($i=0; $i < 100; $i++) { 
            $vinyl = new Vinyl();    
            $vinyl->setMp3("vinyl".$i);
            $vinyl->setTitle("titre du vinyl ".$i);
            $vinyl->setArtiste($faker->name());
            $vinyl->setAnnee(mt_rand(1982,2022));
            $vinyl->setDescription("description du vinyl ".$i);
            $vinyl->setPrice(14.99);
            $vinyl->setQte(mt_rand(1,20));
            $vinyl->setCreatedAt(new \DateTimeImmutable('now'));
            //$vinyl->addCategorie($cat);
            $manager->persist($vinyl);
        }
        $manager->flush();
        // generation de 20 utilisateurs avec Faker
        $i = 0;
        while ($i <= 20) {
            $user = new User;
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setAdresse($faker->address());
            $user->setEmail($faker->email());
            $user->setRoles(["ROLE_USER"]);
            $user->setPassword($faker->sha256());
            $user->setTel($faker->phoneNumber());
            $manager->persist($user);
            

            $i++;
        }
        $manager->flush();
    }
    
}
