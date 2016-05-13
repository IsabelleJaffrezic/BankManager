<?php
/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 13/05/16
 * Time: 20:08
 */

namespace AppBundle\Form;


use AppBundle\Repository\CategorieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointageOperationFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie', EntityType::class, [
                'class' => 'AppBundle\Entity\Categorie',
                'choice_label' => 'libelle',
                'group_by' => 'parent',
                'query_builder' => function(CategorieRepository $qb) {
                    return $qb->findByParentNotNull();
                }
            ])
            ->add('operation', EntityType::class, [
                'class' => 'AppBundle\Entity\Operation',
                'choice_label' => 'libelle',
                'attr' => [
                    'readonly' => true
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'OK']);
        ;
     }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\PointageOperation',
        ]);
    }
}