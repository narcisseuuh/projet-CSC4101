<?php

namespace App\Controller;

use App\Entity\Panier;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $paniers = $entityManager->getRepository(Panier::class)->findAll();
        
        return $this->render('panier/index.html.twig', [
            'paniers' => $paniers
        ]);
    }

    /**
     * Show a Panier
     *
     * @param Integer $id (note that the id must be an integer)
     */
    #[Route('/panier/{id}', name: 'panier_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id) : Response
    {
            $panierRepo = $doctrine->getRepository(Panier::class);
            $panier = $panierRepo->find($id);
            $fruits = $panier->getFruits();
            if ($fruits->isEmpty()) {
                return $this->redirectToRoute('base.html.twig', []);
            }

            return $this->render('panier/show.html.twig', [
                'panier' => $panier
            ]);
    }
}
