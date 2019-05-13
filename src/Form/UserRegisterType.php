<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
            ->add('firstname')
            ->add('lastname')
            ->add('username')
            ->add('email')
            ->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
			'data_class' => User::class,
			'csrf_protection' => false,
			'constraints' => new UniqueEntity([
				'fields' => ['email'],
				'message' => 'Email already in use',
			]),
        ]);
	}
}
