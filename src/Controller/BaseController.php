<?php

namespace App\Controller;

use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    // private $entityManager;
    private $repository;

    public function __construct(/*EntityManagerInterface $entityManager,*/ ObjectRepository $repository)
    {
        // $this->entityManager = $entityManager;
        $this->repository = $repository;
    }

    public function buscarTodos(): Response
    {
        $entityList = $this->repository->findAll();

        return new JsonResponse($entityList);
    }
}