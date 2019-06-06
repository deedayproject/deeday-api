<?php

namespace App\Controller\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\DataHandler\UserHandler;
use App\Form\UserLoginType;
use App\Form\UserRegisterType;
use App\DataHandler\FormHandler;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, FormHandler $formHandler, UserHandler $userHandler): JsonResponse
    {
        $user = $formHandler->handleForm($request, UserRegisterType::class);
        $password = $user->getPassword();
        $user = $userHandler->register($user);
        $user->setPassword($password);
        $user = $userHandler->authenticate($user);

        return $this->json(
            $user,
            JsonResponse::HTTP_CREATED,
            [],
            ['groups' => ['default', 'auth']]
        );
    }

    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(Request $request, FormHandler $formHandler, UserHandler $userHandler): JsonResponse
    {
        $user = $formHandler->handleForm($request, UserLoginType::class);
        $user = $userHandler->authenticate($user);

        return $this->json(
            $user,
            JsonResponse::HTTP_OK,
            [],
            ['groups' => ['default', 'auth']]
        );
    }
}
