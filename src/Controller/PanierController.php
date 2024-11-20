<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use App\Entity\Fruit;
use App\Entity\Panier;
use App\Entity\Member;
use App\Repository\PanierRepository;
use App\Form\PanierType;

#[Route('/panier')]
final class PanierController extends AbstractController
{
    #[Route(name: 'app_panier_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository): Response
    {
        $privateBestOnes = array();
        $member = $this->getUser();
        if ($member) {
            $privatePaniers = $panierRepository->findBy(
                [
                      'published' => false,
                      'creator' => $member
                ]);
            return $this->render('panier/index.html.twig', [
                'paniers' => $panierRepository->findBy(['published' => true]),
                'personnal' => $privatePaniers,
            ]);

        }
        else {
            $private = array();
            return $this->render('panier/index.html.twig', [
                'paniers' => $panierRepository->findBy(['published' => true]),
                'personnal' => $private,
            ]);
        }
        
    }

    #[Route('/new/{id}', name: 'app_panier_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Member $member): Response
    {
        $panier = new Panier();
        $panier->setCreator($member);

        $form = $this->createForm(PanierType::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('app_member_show', ['id' => $member->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier/new.html.twig', [
            'paniers' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_show', methods: ['GET'])]
    public function show(Panier $panier): Response
    {
        return $this->render('panier/show.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Panier::class, $panier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panier);
            $entityManager->flush();

            return $this->redirectToRoute('app_member_show', 
            ['id' => $panier->getCreator()->getId() ], 
            Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier/edit.html.twig', [
            'paniers' => $panier,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_delete', methods: ['POST'])]
    public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($panier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_member_show', 
        ['id' => $panier->getCreator()->getId() ],
        Response::HTTP_SEE_OTHER);
    }



    #[Route('/{panier_id}/fruit/{fruit_id}', methods: ['GET'], name: 'app_panier_fruit_show',requirements: ['panier_id' => '\d+','fruit_id' => '\d+'])] 
    public function fruitShow(#[MapEntity(id: 'panier_id')] 
    Panier $panier,
    #[MapEntity(id: 'fruit_id')]
    Fruit $fruit): Response
    {
        if(! $panier->getFruits()->contains($fruit)) {
            throw $this->createNotFoundException("Fruit introuvable...");
        }

        return $this->render('panier/fruitshow.html.twig', [
            'fruit' => $fruit,
            'panier' => $panier
        ]);
    }
}

