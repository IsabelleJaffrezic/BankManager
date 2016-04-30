<?php
/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 16/04/16
 * Time: 22:17
 */

namespace AppBundle\Form;

use AppBundle\Repository\CategorieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PointageFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add(
                'categorie',
                EntityType::class,
                [
                    'class' => 'AppBundle\Entity\Categorie',
                    'group_by' => 'parent',
                    'query_builder' => function(CategorieRepository $qb) {
                        return $qb->findByParentNotNull();
                    }
                ]
            )
            ->add('save', SubmitType::class, ['attr' => ['class'=>'btn-success']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Pointage',
        ]);
    }
}