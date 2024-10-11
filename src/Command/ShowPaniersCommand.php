<?php

namespace App\Command;

use App\Entity\Panier; // Ensure you import the Panier entity
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:show-paniers',
    description: 'Affiche les paniers de la base de données.',
)]
class ShowPaniersCommand extends Command
{
    protected static $defaultName = 'app:show-paniers';

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('Affiche les paniers de la base de données.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Retrieve all paniers from the repository
        $paniers = $this->entityManager->getRepository(Panier::class)->findAll();
        
        if (!empty($paniers)) {
            $io->title('Liste des Paniers:');
            
            // Map the paniers to an array of strings
            $panierList = array_map(function(Panier $panier) {
                return sprintf('ID: %d, Nom: %s, Nombre de fruits : %d', $panier->getId(), $panier->getNom(), $panier->getPanier()->count());
            }, $paniers);
            
            // Pass the list of strings to the listing method
            $io->listing($panierList);
        } else {
            $io->error('Pas de panier trouvé!');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
