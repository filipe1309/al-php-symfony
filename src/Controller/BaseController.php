<?php

namespace App\Controller;

use App\Helper\ResponseFactory;
use App\Helper\EntidadeFactory;
use App\Helper\ExtratorDadosRequest;
use Psr\Cache\CacheItemPoolInterface;
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
    protected $cache;

    public function __construct(
        EntityManagerInterface $entityManager,
        ObjectRepository $repository,
        EntidadeFactory $factory,
        ExtratorDadosRequest $extratorDadosRequest,
        CacheItemPoolInterface $cache
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $repository;
        $this->factory = $factory;
        $this->extratorDadosRequest = $extratorDadosRequest;
        $this->cache = $cache;
    }
    
    public function novo(Request $request): Response
    {
        $dadosRequest = $request->getContent();
        // try {
            $entidade = $this->factory->criarEntidade($dadosRequest);
        // } catch (\Exception $e) {
        //     return new Response(null, Response::HTTP_PRECONDITION_FAILED);
        // }

        // Entity observar esta entidade
        $this->entityManager->persist($entidade);

        // Envia alteracoes para o banco
        $this->entityManager->flush();

        $cacheItem = $this->cache->getItem($this->cachePrefix() . $entidade->getId());
        $cacheItem->set($entidade);
        $this->cache->save($cacheItem);

        return new JsonResponse($entidade, Response::HTTP_CREATED);
    }

    public function buscarTodos(Request $request): Response
    {
        $informacoesDeOrdenacao = $this->extratorDadosRequest->buscaDadosOrdenacao($request);
        $informacoesDeFiltro = $this->extratorDadosRequest->buscaDadosFiltro($request);
        [$page, $itemsPerPage] = $this->extratorDadosRequest->buscaDadosPaginacao($request);

        $entityList = $this->repository->findBy(
            $informacoesDeFiltro,
            $informacoesDeOrdenacao,
            $itemsPerPage,
            ($page - 1)*$itemsPerPage
        );

        $statusResposta = is_null($entityList) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $fabricaResposta = new ResponseFactory(true, $entityList, $statusResposta, $page, $itemsPerPage);

        return $fabricaResposta->getResponse();
    }

    public function buscarUm(int $id): Response
    {
        $entidade = $this->cache->hasItem($this->cachePrefix() . $id) 
            ? $this->cache->getItem($this->cachePrefix() . $id)->get()
            : $this->repository->find($id);
        $statusResposta = is_null($entidade) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $fabricaResposta = new ResponseFactory(true, $entidade, $statusResposta);

        return $fabricaResposta->getResponse();
    }

    public function remove($id): Response
    {
        $entidade = $this->repository->find($id);
        $this->entityManager->remove($entidade);
        $this->entityManager->flush();

        $this->cache->deleteItem($this->cachePrefix() . $id);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    public function atualiza(int $id, Request $request): Response
    {
        $corpoRequisiscao = $request->getContent();
        $entidadeEnviada = $this->factory->criarEntidade($corpoRequisiscao);
        $entidadeExistente = $this->repository->find($id);

        if (is_null($entidadeExistente)) {
            $fabricaResposta = new ResponseFactory(false, 'Recurso nao encontrado', Response::HTTP_NOT_FOUND);
            return $fabricaResposta->getResponse();
        }

        $this->atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);

        // Envia alteracoes para o banco
        $this->entityManager->flush();

        $cacheItem = $this->cache->getItem($this->cachePrefix() . $id);
        $cacheItem->set($entidadeExistente);
        $this->cache->save($cacheItem);

        $fabricaResposta = new ResponseFactory(true, $entidadeExistente, Response::HTTP_OK);
        return $fabricaResposta->getResponse();
    }

    abstract public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);
    abstract public function cachePrefix(): string;
}