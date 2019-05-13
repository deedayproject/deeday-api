<?php

namespace App\DataHandler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;

class UserHandler
{
    private $em;
    private $passwordEncoder;

    public function __construct(ObjectManager $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function register(User $user): User
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
