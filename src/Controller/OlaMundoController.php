<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class OlaMundoController
{
    /**
     * @Route("/ola")
     */
    public function olaMundoAction(Request $request): Response
    {
        $pathInfo  = $request->getPathInfo();
        // $parametro = $request->query->get('parametro');
        $parametro = $request->get('parametro');
        $query = $request->query->all();
        return new JsonResponse([
            'mensagem' => 'Ola mundo!',
            'pathInfo' => $pathInfo,
            'parametro' => $parametro,
            'query' => $query]);
    }
}