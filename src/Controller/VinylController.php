<?php

namespace App\Controller;

use App\Entity\Vinyl;
use App\Form\VinylType;
use App\Repository\VinylRepository;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use \Gumlet\ImageResize;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/vinyl")
 */
class VinylController extends AbstractController
{
    /**
     * @Route("/", name="vinyl_index", methods={"GET"})
     */
    public function index(VinylRepository $vinylRepository): Response
    {
        $resultVinyl = $vinylRepository->findAll();
        return $this->render('vinyl/index.html.twig', [
            'vinyls' => $resultVinyl,
        ]);
    }

    /**
     * @Route("/new", name="vinyl_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $vinyl = new Vinyl();
        $form = $this->createForm(VinylType::class, $vinyl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            date_default_timezone_set('Europe/Paris');
            $vinyl->setCreatedAt(new \DateTimeImmutable('now'));
            
            // gerer les categories
            $categories = $form->get('categorie')->getData();
            foreach ($categories as $value) {
                $vinyl->addCategorie($value);
            }

            // uplaod du fichier mp3
            $mp3 = $form->get('mp3')->getData();
            if($mp3){
                $directory = str_replace("\\", "/", $this->getParameter('mp3_directory'))."/";
                $originalName = pathinfo($mp3->getClientOriginalName(), PATHINFO_FILENAME).".mp3";
                $mp3->move($directory, $originalName);
                $vinyl->setMp3($originalName);
            }
            $entityManager->persist($vinyl);
            $entityManager->flush();

            //je récupere les informations de l'image uploadée
            $image = $form->get('image')->getData();
            if ($image) {
                // je recupère l'id du vinyl enregistré pour créer le nom de mon fichier
                $directory = str_replace("\\", "/", $this->getParameter('cover_directory'))."/";
                $newImageName = $vinyl->getId() . ".jpg";
                $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $originalName = $originalName . "." . $image->guessExtension();
                // equivalent du move_upload_file() de php
                $image->move($directory, $originalName);
                // je copie et redimenssionne l'image originale uploadée
                $newImage = new ImageResize($directory . $originalName);
                $newImage->resizeToWidth(500);
                $newImage->save($directory . $newImageName, IMAGETYPE_JPEG);
                
                // creation d'une miniature
                $newImage = new ImageResize($directory . $originalName);
                $newImage->resizeToWidth(70);
                $newImage->save($directory ."thumbnail/". $newImageName, IMAGETYPE_JPEG);
                // je detruis l'image original
                unlink($directory . $originalName);
            }
            return $this->redirectToRoute('vinyl_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vinyl/new.html.twig', [
            'vinyl' => $vinyl,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="vinyl_show", methods={"GET"})
     */
    public function show(Vinyl $vinyl): Response
    {
        return $this->render('vinyl/show.html.twig', [
            'vinyl' => $vinyl,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vinyl_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Vinyl $vinyl, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VinylType::class, $vinyl);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('vinyl_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vinyl/edit.html.twig', [
            'vinyl' => $vinyl,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="vinyl_delete", methods={"POST"})
     */
    public function delete(Request $request, Vinyl $vinyl, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vinyl->getId(), $request->request->get('_token'))) {
            $entityManager->remove($vinyl);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vinyl_index', [], Response::HTTP_SEE_OTHER);
    }
}
