<?php

namespace App\Controller;

use App\Entity\Especialidade;
use Doctrine\ORM\EntityManagerInterface;
use App\Helper\EspecialidadeFactory;
use App\Repository\EspecialidadeRepository;

class EspecialidadesController extends BaseController
{
    public function __construct(EntityManagerInterface $entityManager, EspecialidadeRepository $repository, EspecialidadeFactory $factory)
    {
        parent::__construct($entityManager, $repository, $factory);
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
