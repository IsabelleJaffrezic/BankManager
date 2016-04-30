<?php
/**
 * Created by PhpStorm.
 * User: zazhu
 * Date: 09/04/16
 * Time: 21:01
 */

namespace AppBundle\Form;


use AppBundle\Repository\CategorieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategorieFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            $form->add(
                'parent',
                EntityType::class,
                [
                    'class' => 'AppBundle\Entity\Categorie',
                    'required' => false,
                    'query_builder' => function(CategorieRepository $qb) use ($data) {
                        return $qb->findByParentNull($data);
                    }
                ]);
        });

        $builder
            ->add('libelle')
            ->add('save', SubmitType::class, ['attr' => ['class'=>'btn-success']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Categorie',
        ]);
    }
}