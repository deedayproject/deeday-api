<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use App\Entity\User;
use App\Repository\UserRepository;

class UserLoginType extends AbstractType
{
	private $userRepository;
	private $passwordEncoderInterface;

	public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoderInterface)
	{
		$this->userRepository = $userRepository;
		$this->passwordEncoderInterface = $passwordEncoderInterface;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('email', TextType::class, [
				'constraints' => [
					new NotBlank,
					new Email,
				],
			])
			->add('password', TextType::class, [
				'constraints' => new Callback([$this, 'checkPassword']),
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
	
	public function checkPassword($value, ExecutionContextInterface $context)
	{
		$form = $context->getRoot();
		$data = $form->getData();
		
		$user = $this->userRepository->findOneBy(['email' => $data->getEmail()]);
		if (
			null === $user ||
			!$this->passwordEncoderInterface->isPasswordValid($user, $value)
		) {
			$context
				->buildViolation('Email / password combination is not valid.')
				->addViolation();
		}
	}
}
