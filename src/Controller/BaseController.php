<?php

namespace App\Controller;

use App\Helper\EntidadeFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    protected $entityManager;
    protected $repository;
    protected $factory;

    public function __construct(EntityManagerInterface $entityManager, ObjectRepository $repository, EntidadeFactory $factory)
    {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->factory = $factory;
    }
    
    public function novo(Request $request): Response
    {
        $dadosRequest = $request->getContent();
        try {
            $entidade = $this->factory->criarEntidade($dadosRequest);
        }
        catch (\Exception $e) {
            return new Response(null, Response::HTTP_PRECONDITION_FAILED);
        }

        // Entity observar esta entidade
        $this->entityManager->persist($entidade);

        // Envia alteracoes para o banco
        $this->entityManager->flush();

        return new JsonResponse($entidade, Response::HTTP_CREATED);
    }

    public function buscarTodos(): Response
    {
        $entityList = $this->repository->findAll();

        return new JsonResponse($entityList);
    }

    public function buscarUm(int $id): Response
    {
        return new JsonResponse( $this->repository->find($id));
    }

    public function remove($id): Response
    {
        $entidade = $this->repository->find($id);
        $this->entityManager->remove($entidade);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    public function atualiza(int $id, Request $request): Response
    {
        $corpoRequisiscao = $request->getContent();

        $entidadeEnviada = $this->factory->criarEntidade($corpoRequisiscao);

        $entidadeExistente = $this->repository->find($id);

        if (is_null($entidadeExistente)) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        $this->atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);

        // Envia alteracoes para o banco
        $this->entityManager->flush();

        return new JsonResponse($entidadeExistente);
    }

    abstract public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);
}