<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Cuisine;
use App\Entity\Fruit;
use App\Form\FruitType;
use App\Repository\FruitRepository;

#[Route('/fruit')]
final class FruitController extends AbstractController
{
	#[Route(name: 'app_fruit_index', methods: ['GET'])]
    public function index(FruitRepository $fruitRepo): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $tvshows = $fruitRepo->findAll();
        }
        else {
            $member = $this->getUser();
            dump($this);
            $fruits = $fruitRepo->findMemberFruit($member);
        }
        return $this->render('fruit/index.html.twig', [
            'tv_shows' => $fruitRepo->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_fruit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Cuisine $cuisine): Response
    {
        $fruit = new Fruit();
        $fruit->setCuisine($cuisine);

        $form = $this->createForm(FruitType::class, $fruit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imagefile = $fruit->getImageFile();
            $entityManager->persist($fruit);
            $entityManager->flush();

            return $this->redirectToRoute('app_cuisine',
			['id' => $cuisine->getId()], 
			Response::HTTP_SEE_OTHER);
        }

        return $this->render('fruit/new.html.twig', [
            'fruit' => $fruit,
            'form' => $form,
        ]);
    }

	#[Route('/{id}', name: 'fruit_show', methods: ['GET'])]
	public function show(Fruit $fruit): Response
	{
		return $this->render('fruit/show.html.twig', 
		['fruit' => $fruit]);
	}

	#[Route('/{id}/edit', name: 'app_fruit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fruit $fruit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FruitType::class, $fruit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('cuisine_show', 
			['id' => $fruit->getCuisine()->getId()], 
			Response::HTTP_SEE_OTHER);
        }

        return $this->render('fruit/edit.html.twig', [
            'fruit' => $fruit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_fruit_delete', methods: ['POST'])]
    public function delete(Request $request, Fruit $fruit, EntityManagerInterface $entityManager): Response
    {
        $cuisine = $fruit->getCuisine();
        if ($this->isCsrfTokenValid('delete'.$fruit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($fruit);
            $entityManager->flush();
        }

        $cuisine = $fruit->getCuisine();
        if ($cuisine) {
            return $this->redirectToRoute('cuisine_show', 
			['id' => $cuisine->getId()], 
			Response::HTTP_SEE_OTHER);
        }
    }
}