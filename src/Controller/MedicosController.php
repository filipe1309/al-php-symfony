<?php

namespace App\Controller;

use App\Entity\Medico;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class MedicosController
{
    /**
     * @Route("/medicos")
     */
    public function novo(Request $request): Response
    {
        $corpoRequisiscao = $request->getContent();
        $dadoEmJson = json_decode($corpoRequisiscao);

        $medico = new Medico();
        $medico->crm = $dadoEmJson->crm;
        $medico->nome = $dadoEmJson->nome;

        return new JsonResponse($medico);
    }
}