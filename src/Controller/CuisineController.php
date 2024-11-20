<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Cuisine;
use App\Entity\Fruit;

class CuisineController extends AbstractController
{
    #[Route('/cuisine', name: 'app_cuisine')]
    #[IsGranted('ROLE_USER')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager= $doctrine->getManager();
        $cuisines = $entityManager->getRepository(Cuisine::class)->findAll();


        return $this->render('cuisine/index.html.twig',
                [ 'cuisines' => $cuisines ]
            );
    }

    /**
     *
     * @param Integer $id (note that the id must be an integer)
     */
    
    #[Route('/cuisine/{id}', name: 'cuisine_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id) : Response
    {
            $cuisineRepo = $doctrine->getRepository(Cuisine::class);
            $cuisine = $cuisineRepo->find($id);
       
            return $this->render('cuisine/show.html.twig',
                    [ 'cuisine' => $cuisine ]);
    }

}
