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
        // Guard clauses
        $this->checkAllProperties($dadoEmJson);

        $especialidadeId = $dadoEmJson->especialidadeId;
        $especialidade = $this->especialidadeRepository->find($especialidadeId);

        $medico = new Medico();
        $medico
            ->setCrm($dadoEmJson->crm)
            ->setNome($dadoEmJson->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }

    private function checkAllProperties($dadoEmJson): void
    {
        if(!property_exists($dadoEmJson, 'nome')) {
            throw new EntityFactoryException('Medico precisa de nome'); 
        }
        
        if(!property_exists($dadoEmJson, 'crm')) {
            throw new EntityFactoryException('Medico precisa de crm'); 
        }

        if(!property_exists($dadoEmJson, 'especialidadeId')) {
            throw new EntityFactoryException('Medico precisa de especialidade'); 
        }
    }
}