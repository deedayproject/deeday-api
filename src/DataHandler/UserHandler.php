<?php

namespace App\DataHandler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Repository\UserRepository;

class UserHandler
{
    private $em;
    private $passwordEncoder;
    private $userRepository;
    private $parameterBag;

    public function __construct(
        ObjectManager $em,
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository,
        ParameterBagInterface $parameterBagInterface
    ) {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
        $this->parameterBag = $parameterBagInterface;
    }

    public function register(User $user): User
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    public function authenticate(User $user): User
    {
        $opts = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/json',
                'content' => \json_encode([
                    'username' => $user->getEmail(),
                    'password' => $user->getPassword(),
                ]),
            ],
        ];

        $context = stream_context_create($opts);
        $host = $this->parameterBag->get('env_host').'/api/login_check';
        $response = file_get_contents($host, false, $context);
        $data = \json_decode($response, true);
        $user = $this->userRepository->findOneBy(['email' => $user->getEmail()]);

        $user
            ->setRefreshToken($data['refresh_token'])
            ->setToken($data['token']);

        return $user;
    }
}
