<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\ExtratorDadosRequest;
use App\Helper\EspecialidadeFactory;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\EspecialidadeRepository;

class EspecialidadesController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $repository,
        EspecialidadeFactory $factory,
        ExtratorDadosRequest $extratorDadosRequest
    ) {
        parent::__construct($entityManager, $repository, $factory, $extratorDadosRequest);
    }

    /**
     * @param Especialidade $entidadeExistente
     * @param Especialidade $entidadeEnviada
     */
    public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada)
    {
        $entidadeExistente->setDescricao($entidadeEnviada->getDescricao());
    }
}
