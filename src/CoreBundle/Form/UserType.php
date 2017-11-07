<?php
namespace CoreBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType {
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ->add('codeParrain', TextType::class)
        ->add('nom', TextType::class)
        ->add('prenoms', TextType::class)
        ->add('naissance', DateType::class, [
            'widget' => 'choice',
            'years' => range(date('Y') - 18, date('Y') - 100),
            'months' => range(1, 12),
            'days' => range(1, 31),
        ])
        ->add('sexe', EntityType::class, [
            'class' => 'CoreBundle:Sexe',
            'multiple' => false,
            'choice_label' => 'sexe'
        ])
        ->add('profession', TextType::class, ['required' => false])
        ->add('tel', TextType::class)
        ->add('email', EmailType::class, ['required' => false])
        ->add('pays', CountryType::class)
        ->add('ville', TextType::class)
        ->add('adresse', TextType::class, ['required' => false])
        ->add('password', PasswordType::class)
        ->add('Valider', SubmitType::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'CoreBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'corebundle_user';
    }


}