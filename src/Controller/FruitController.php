<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Fruit;

class FruitController extends AbstractController
{
	/**
	 * Affiche un fruit
	 * 
	 * @param Integer $id (note that the id must be an integer)
	 */
	#[Route('/{id}', name: 'fruit_show', requirements: ['id' => '\d+'])]
	public function show(ManagerRegistry $doctrine, $id): Response
	{
		$fruits = $doctrine->getRepository(Fruit::class);
		$fruit = $fruits->find($id);

		return $this->render('fruit/show.html.twig', [
			'fruit' => $fruit,
		]);
	}
}