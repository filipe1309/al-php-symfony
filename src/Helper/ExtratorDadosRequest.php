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
        $paginalAtual = array_key_exists('page', $informacoesDeFiltro)
            ? $informacoesDeFiltro['page']
            : 1;
        unset($informacoesDeFiltro['page']);
        $itensPorPagina = array_key_exists('itensPorPagina', $informacoesDeFiltro)
            ? $informacoesDeFiltro['itensPorPagina']
            : 5;
        unset($informacoesDeFiltro['itensPorPagina']);

        return [$informacoesDeFiltro, $informacoesDeOrdenacao, $paginalAtual, $itensPorPagina];
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
        [, , $paginalAtual, $itensPorPagina] = $this->buscaDadosRequest($request);

        return [$paginalAtual, $itensPorPagina];
    }
}