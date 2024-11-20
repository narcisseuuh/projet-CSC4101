<?php

namespace App\Form;

use App\Entity\Fruit;
use App\Entity\Panier;
use App\Repository\FruitRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PanierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $panier = $options['data'] ?? null;
        $member = $panier->getCreator(); 

        $builder
            ->add('description')
            ->add('creator', null, [
                'disabled' => true,])
            ->add('fruits', EntityType::class,
            [
                'by_reference' => false,
                'class' => Fruit::class,
                'multiple' => true,
                'expanded' => true,
                'query_builder' => function (FruitRepository $er) use ($member) {
                                      return $er->createQueryBuilder('o')
                                                ->leftJoin('o.cuisine', 'i')
                                                ->leftJoin('i.member', 'm')
                                                ->andWhere('m.id = :memberId')
                                                ->setParameter('memberId', $member->getId())
                                                ;}
                
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Panier::class,
        ]);
    }
}
