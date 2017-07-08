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
            ->add('file', FileType::class, array('required' => false, 'label' => 'label_photo', 'attr' => array()))
            ->add('lastName', TextType::class, array('label' => 'label_lastname', 'attr' => array('class' =>'form-control', 'placeholder' => 'label_lastname')))
            ->add('firstName', TextType::class, array('label' => 'label_firstname', 'attr' => array('class' =>'form-control', 'placeholder' => 'label_firstname')))
            ->add('school', null, array('class' => 'AppBundle:School',
                'expanded' => false,
                'required' => true,
                'attr' => array('class' =>'form-control'),
                'label' => 'label_school',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                    ->orderBy('s.name', 'ASC');
                },
                'placeholder' => 'label_school',
                'empty_data'  => null,
            ));

        if (!empty($school)) {
            $builder->add('studentGroup', EntityType::class, array('class' => 'AppBundle:StudentGroup',
                'expanded' => false,
                'required' => true,
                'attr' => array('class' =>'form-control'),
                'label' => 'label_student_group',
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
                'label' => 'label_student_group',
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
            'translation_domain' => 'messages'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'user_edit_profile';
    }
}