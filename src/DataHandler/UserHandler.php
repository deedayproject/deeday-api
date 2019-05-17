<?php

namespace App\DataHandler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use App\Repository\UserRepository;

class UserHandler
{
    private $em;
    private $passwordEncoder;
    private $JWTManager;
    private $successHandler;
    private $userRepository;

    public function __construct(
        ObjectManager $em,
        UserPasswordEncoderInterface $passwordEncoder,
        JWTTokenManagerInterface $JWTManager,
        AuthenticationSuccessHandler $successHandler,
        UserRepository $userRepository
    ) {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->JWTManager = $JWTManager;
        $this->successHandler = $successHandler;
        $this->userRepository = $userRepository;
    }

    public function register(User &$user): User
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
    
    public function login(User &$user): User
    {
        $user = $this->userRepository->findOneBy(['email' => $user->getEmail()]);
        $token = $this->JWTManager->create($user);
        $this->successHandler->handleAuthenticationSuccess($user, $token);
        
        $user->setToken($token);

        return $user;
    }
}
