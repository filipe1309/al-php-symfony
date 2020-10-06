<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @param Medico $entidadeExistente
     * @param Medico $entidadeEnviada
     */
    public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada)
    {
        $entidadeExistente
            ->setCrm($entidadeEnviada->getCrm())
            ->setNome($entidadeEnviada->getNome())
            ->setEspecialidade($entidadeEnviada->getEspecialidade());
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