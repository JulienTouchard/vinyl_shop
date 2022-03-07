<?php

namespace App\Controller;

use App\Repository\VinylRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RechercheController extends AbstractController
{
    /**
     * @Route("/recherche", name="recherche", methods={"GET", "POST"})
     */
    public function index(VinylRepository $vinylRepository): Response
    {
        $output[] = array();
        $results = [];
        $rechercheArtiste = "";
        if(isset($_POST['submited']) && !empty($_POST['submited'])){
            $rechercheArtiste = $_POST['rechercheArtiste'];
            $results = $vinylRepository->findRecherche($rechercheArtiste,'artiste');
            //dd($results);
        }
        
        return $this->render('recherche/index.html.twig', [
            'controller_name' => 'RechercheController',
            'rechercheArtiste' => $rechercheArtiste,
            'results' => $results
        ]);
    }
    /**
     * @Route("/ajaxTitle", name="ajaxTitle", methods={"GET", "POST"})
     */
    public function ajaxTitle(VinylRepository $vinylRepository): Response
    {
        $results = [];
        $output = [];
        $rechercheTitle = "";
        if(isset($_POST['rechercheTitle']) && !empty($_POST['rechercheTitle'])){
            $rechercheTitle = $_POST['rechercheTitle'];
            $results = $vinylRepository->findRecherche($rechercheTitle,'title');
            //echo json_encode($results);
            foreach ($results as $result){
                $output[] = array('id'=>$result->getId(),'title'=>$result->getTitle(),'artiste'=>$result->getArtiste());
            }
            return new JsonResponse($output);
        }
    }
}
