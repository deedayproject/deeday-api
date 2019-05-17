<?php

namespace App\Controller\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\DataHandler\UserHandler;
use App\Form\UserLoginType;
use App\Form\UserRegisterType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function register(Request $request, UserHandler $userHandler): JsonResponse
    {
        $form = $this->createForm(UserRegisterType::class);
        $form->submit($request->request->all());

        if (!($form->isSubmitted() && $form->isValid())) {
            $error = $form->getErrors(true)->current()->getMessage();
            throw new BadRequestHttpException($error);
        }

        $user = $form->getData();
        $userHandler->register($user);
        $userHandler->login($user);

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
    public function login(Request $request, UserHandler $userHandler): JsonResponse
    {
        $form = $this->createForm(UserLoginType::class);
        $form->submit($request->request->all());

        if (!($form->isSubmitted() && $form->isValid())) {
            $error = $form->getErrors(true)->current()->getMessage();
            throw new BadRequestHttpException($error);
        }

        $user = $form->getData();
        $user = $userHandler->login($user);

        return $this->json(
            $user,
            JsonResponse::HTTP_OK,
            [],
            ['groups' => 'default']
        );
    }
}
