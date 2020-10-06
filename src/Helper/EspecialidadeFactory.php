<?php

namespace App\Helper;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;

class EspecialidadeFactory implements EntidadeFactory
{
    private $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function criarEntidade(string $json): Especialidade
    {
        $dadoEmJson = json_decode($json);
        
        $especialidade = new Especialidade();
        $especialidade
            ->setDescricao($dadoEmJson->descricao);

        return $especialidade;
    }
}