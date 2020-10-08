<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class ExtratorDadosRequest
{
    private function buscaDadosRequest(Request $request)
    {
        
        $informacoesDeFiltro = $request->query->all();
        
        // Sort
        $informacoesDeOrdenacao = array_key_exists('sort', $informacoesDeFiltro)
            ? $informacoesDeFiltro['sort']
            : null;
        unset($informacoesDeFiltro['sort']);

        // Pagination
        $page = array_key_exists('page', $informacoesDeFiltro)
            ? $informacoesDeFiltro['page']
            : 1;
        unset($informacoesDeFiltro['page']);
        $itemsPerPage = array_key_exists('itemsPerPage', $informacoesDeFiltro)
            ? $informacoesDeFiltro['itemsPerPage']
            : 5;
        unset($informacoesDeFiltro['itemsPerPage']);

        return [$informacoesDeFiltro, $informacoesDeOrdenacao, $page, $itemsPerPage];
    }

    public function buscaDadosOrdenacao(Request $request)
    {
        [, $informacoesDeOrdenacao] = $this->buscaDadosRequest($request);

        return $informacoesDeOrdenacao;
    }

    public function buscaDadosFiltro(Request $request)
    {
        [$informacoesDeFiltro] = $this->buscaDadosRequest($request);

        return $informacoesDeFiltro;
    }

    public function buscaDadosPaginacao(Request $request)
    {
        [, , $page, $itemsPerPage] = $this->buscaDadosRequest($request);

        return [$page, $itemsPerPage];
    }
}