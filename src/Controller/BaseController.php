<?php

namespace App\Controller;

use App\Helper\EntidadeFactory;
use App\Helper\ExtratorDadosRequest;
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
    protected $extratorDadosRequest;

    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectRepository $repository,
        EntidadeFactory $factory,
        ExtratorDadosRequest $extratorDadosRequest
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->extratorDadosRequest = $extratorDadosRequest;
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

    public function buscarTodos(Request $request): Response
    {
        $informacoesDeOrdenacao = $this->extratorDadosRequest->buscaDadosOrdenacao($request);
        $informacoesDeFiltro = $this->extratorDadosRequest->buscaDadosFiltro($request);
        [$paginalAtual, $itensPorPagina] = $this->extratorDadosRequest->buscaDadosPaginacao($request);

        $entityList = $this->repository->findBy(
            $informacoesDeFiltro,
            $informacoesDeOrdenacao,
            $itensPorPagina,
            ($paginalAtual - 1)*$itensPorPagina
        );

        $httpStatus = Response::HTTP_OK;
        if ($entityList) {
            $httpStatus = Response::HTTP_PARTIAL_CONTENT;
        }

        return new JsonResponse($entityList, $httpStatus);
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