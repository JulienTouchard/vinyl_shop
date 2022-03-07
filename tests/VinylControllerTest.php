<?php
namespace App\Test\Controller;

use App\Repository\UserRepository;
use App\Repository\VinylRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VinylControllerTest extends WebTestCase{

    public function testVinyl(){
        $routes = ['/vinyl/','/vinyl/new','/vinyl/{id}','/vinyl/{id}/edit'];
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        //$nbVinyl = $container->get(VinylRepository::class)->count([]);
        $userAdmin = $userRepository->findBy(['email'=>'admin@azerty.com']);
        //print_r($userAdmin);
        

        $client->loginUser($userAdmin)[0];
        $client->request('GET','/vinyl');
        //echo $client->getResponse()->getContent();
        //echo "Status code ".$client->getResponse()->getStatusCode();
        $this->assertEquals(200,$client->getResponse()->getStatusCode(), "La page de la requete ne fonction pas");
    }
    
}