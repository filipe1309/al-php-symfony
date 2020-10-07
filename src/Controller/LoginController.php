<?php

namespace App\Controller;

use Firebase\JWT\JWT;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    private $repository;
    private $encoder;

    public function __construct(UserRepository $repository, UserPasswordEncoderInterface $encoder) {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request): JsonResponse
    {
        $dadoEmJson = json_decode($request->getContent());
        
        if (is_null($dadoEmJson->usuario) || is_null($dadoEmJson->senha)) {
            return new JsonResponse(['erro' => 'favor enviar usuario/senha'], Response::HTTP_BAD_REQUEST);
        }
        
        $user = $this->repository->findOneBy(['username' => $dadoEmJson->usuario]);
        
        if (!$user || !$this->encoder->isPasswordValid($user, $dadoEmJson->senha)) {
            return new JsonResponse(['erro' => 'Usuario/Senha invalidos'], Response::HTTP_UNAUTHORIZED);
        }

        $token = JWT::encode(['username' => $user->getUsername()], 'chave');

        return new JsonResponse(['access_token' => $token], Response::HTTP_OK);
    }
}
