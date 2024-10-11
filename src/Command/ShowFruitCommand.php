<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Fruit;

#[AsCommand(
    name: 'app:show-fruit',
    description: 'Affiche les fruits de la base de données.',
)]
class ShowFruitCommand extends Command
{
    protected static $defaultName = 'app:show-fruit';
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Affiche les fruits de la base de données.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fruits = $this->entityManager->getRepository(Fruit::class)->findAll();
        if(!empty($fruits)) {
            $io->title('Liste des Fruits:');
            $fruitList = array_map(function(Fruit $fruit) {
                return sprintf('ID: %d, Nom: %s', $fruit->getId(), $fruit->getNom());
            }, $fruits);
            $io->listing($fruitList);
        } else {
            $io->error('Pas de fruit trouvé!');
            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
