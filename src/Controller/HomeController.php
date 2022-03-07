<?php

namespace App\Controller;

use App\Repository\VinylRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(VinylRepository $vinylRepository ): Response
    {
        //dd($vinylRepository->findAll());
        $randomVinyls = $vinylRepository->findRand(5);
        $tbPlaylist = [];
        foreach ($randomVinyls as $key => $value) {
            array_push($tbPlaylist,
                ['id'=> $value->getId(),
                'mp3' => $value->getMp3(),
                'title' => $value->getTitle(),
                'artiste' => $value->getArtiste()]
            );
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'tbPlaylist' => json_encode( $tbPlaylist)
        ]);
    }
}
