<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class EditProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $builder->getData();
        $school = (!empty($user)) ? $user->getSchool() : null;
      
        $now = new \DateTime();

        $builder
            ->add('file', FileType::class, array('required' => false, 'label' => 'Photo', 'attr' => array()))
            ->add('lastName', TextType::class, array('label' => 'Nom', 'attr' => array('class' =>'form-control', 'placeholder' => 'nom')))
            ->add('firstName', TextType::class, array('label' => 'Prénom', 'attr' => array('class' =>'form-control', 'placeholder' => 'prenom')))
            ->add('school', null, array('class' => 'AppBundle:School',
                'expanded' => false,
                'required' => true,
                'attr' => array('class' =>'form-control'),
                'label' => 'Votre école',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                    ->orderBy('s.name', 'ASC');
                },
                'placeholder' => 'Choisir votre école',
                'empty_data'  => null,
            ));

        if (!empty($school)) {
            $builder->add('studentGroup', EntityType::class, array('class' => 'AppBundle:StudentGroup',
                'expanded' => false,
                'required' => true,
                'attr' => array('class' =>'form-control'),
                'label' => 'Votre classe',
                'query_builder' => function (EntityRepository $er) use ( $school, $now) {
                    return $er->createQueryBuilder('s')
                    ->join('s.promotion', 'p')
                    ->andWhere('p.startDate <= :now and p.endDate >= :now')
                    ->andWhere('s.school = :school')
                    ->orderBy('s.name', 'ASC')
                    ->setParameters(array('school' => $school->getId(), 'now' => $now));
                }));
        }
        else // student 
        {
            $builder->add('studentGroup', EntityType::class, array('class' => 'AppBundle:StudentGroup',
                'expanded' => false,
                'required' => true,
                'attr' => array('class' =>'form-control'),
                'label' => 'Votre classe',
                'query_builder' => null));
        }

            
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'user_edit_profile';
    }
}