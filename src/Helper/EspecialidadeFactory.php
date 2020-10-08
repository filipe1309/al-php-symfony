<?php

namespace App\Helper;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;

class EspecialidadeFactory implements EntidadeFactory
{
    public function criarEntidade(string $json): Especialidade
    {
        $dadoEmJson = json_decode($json);
        if(!property_exists($dadoEmJson, 'descricao')) {
            throw new EntityFactoryException('Especialidade precisa de descricao'); 
        }       
        $especialidade = new Especialidade();
        $especialidade
            ->setDescricao($dadoEmJson->descricao);

        return $especialidade;
    }
}