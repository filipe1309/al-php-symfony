<?php

namespace App\Controller;

use App\Entity\Especialidade;
use Doctrine\ORM\EntityManagerInterface;
use App\Helper\EspecialidadeFactory;
use App\Repository\EspecialidadeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class EspecialidadesController extends BaseController
{
    private $factory;

    public function __construct(EntityManagerInterface $entityManager, EspecialidadeRepository $repository, EspecialidadeFactory $factory)
    {
        parent::__construct($entityManager, $repository);
        $this->factory = $factory;
    }

    /**
     * @Route("/especialidades", methods={"POST"})
     */
    public function nova(Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $especialidade = $this->factory->criarEntidade($dadosRequest);

        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    /**
     * @Route("/especialidades/{id}", methods={"PUT"})
     */
    public function atualiza($id, Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $dadosEmJson = json_decode($dadosRequest);

        $especialidade = $this->repository->find($id);
        $especialidade->setDescricao($dadosEmJson->descricao);

        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }
}
