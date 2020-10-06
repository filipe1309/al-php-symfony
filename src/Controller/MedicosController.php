<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class MedicosController extends BaseController
{
    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $factory, MedicoRepository $repository)
    {
        parent::__construct($entityManager, $repository, $factory);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function atualiza(int $id, Request $request): Response
    {
        $corpoRequisiscao = $request->getContent();

        $medicoEnviado = $this->factory->criarEntidade($corpoRequisiscao);

        $medicoExistente = $this->buscaMedico($id);

        if (is_null($medicoExistente)) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        $medicoExistente->setCrm($medicoEnviado->getCrm());
        $medicoExistente->setNome($medicoEnviado->getNome());

        // Envia alteracoes para o banco
        $this->entityManager->flush();

        return new JsonResponse($medicoExistente);
    }

    public function buscaMedico(int $id)
    {
        return $this->repository->find($id);
    }

    /**
     * @Route("/especialidades/{especialidadeId}/medicos", methods={"GET"})
     */
    public function buscaPorespecialidade(int $especialidadeId): Response
    {
        $medicos = $this->repository->findBy(['especialidade' => $especialidadeId]);
        
        return new JsonResponse($medicos);
    }
}