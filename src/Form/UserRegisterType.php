<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use App\Entity\User;

class UserRegisterType extends AbstractType
{
	/**
	 * Length: 8
	 * Capital: 1
	 * Digits: 1
	 */
	const PASSWORD_PATTERN = "/^(?=(?:.*[A-Z]){1,})(?=(?:.*\d){1,})(.{8,})$/";

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('firstname', TextType::class, [
				'constraints' => [
					new NotBlank,
				],
			])
			->add('lastname', TextType::class, [
				'constraints' => [
					new NotBlank,
				],
			])
			->add('username', TextType::class, [
				'constraints' => [
					new NotBlank,
					new Length(['min' => 4])
				],
			])
			->add('email', EmailType::class, [
				'constraints' => [
					new NotBlank,
					new Email,
				],
			])
			->add('password', PasswordType::class, [
				'constraints' => [
					new Length(['min' => 8]),
					new Regex(['pattern' => self::PASSWORD_PATTERN]),
				]
			])
		;
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
			'csrf_protection' => false,
		]);
	}
}
