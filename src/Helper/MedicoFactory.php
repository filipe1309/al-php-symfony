<?php

namespace App\Helper;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;

class MedicoFactory implements EntidadeFactory
{
    private $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function criarEntidade(string $json): Medico
    {
        $dadoEmJson = json_decode($json);
        if(!property_exists($dadoEmJson, 'nome') || !property_exists($dadoEmJson, 'crm') || !property_exists($dadoEmJson, 'especialidadeId')) {
            throw new EntityFactoryException('Medico precisa de nome, crm e especialidade'); 
        }
        $especialidadeId = $dadoEmJson->especialidadeId;
        $especialidade = $this->especialidadeRepository->find($especialidadeId);

        $medico = new Medico();
        $medico
            ->setCrm($dadoEmJson->crm)
            ->setNome($dadoEmJson->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }
}