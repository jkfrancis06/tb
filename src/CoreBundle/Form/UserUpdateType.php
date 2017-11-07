<?php
namespace CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserUpdateType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ->remove('naissance')
        ->remove('code')
        ->remove('codeParrain')
        ->remove('sexe');
    }
    
    public function getParent() {
        return UserType::class;
    }
}