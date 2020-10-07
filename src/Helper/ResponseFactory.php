<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseFactory
{
    private $sucesso;
    private $conteudoResposta;
    private $statusResposta;
    private $paginalAtual;
    private $itensPorPagina;

    public function __construct(
        bool $sucesso,
        $conteudoResposta,
        int $statusResposta = Response::HTTP_OK,
        int $paginalAtual = null,
        int $itensPorPagina = null
    ) {
        $this->sucesso = $sucesso;
        $this->conteudoResposta = $conteudoResposta;
        $this->statusResposta = $statusResposta;
        $this->paginalAtual = $paginalAtual;
        $this->itensPorPagina = $itensPorPagina;
    }

    public function getResponse(): JsonResponse
    {
        $conteudoResposta = [
            'sucesso' => $this->sucesso,
            'paginalAtual' => $this->paginalAtual,
            'itensPorPagina' => $this->itensPorPagina,
            'conteudoResposta' => $this->conteudoResposta,
            // TODO Add total de resultados
            // TODO Add URL da proxima pagina e anterior
        ];

        if (is_null($this->paginalAtual)) {
            unset($conteudoResposta['paginalAtual']);
            unset($conteudoResposta['itensPorPagina']);
        }

        return new JsonResponse($conteudoResposta, $this->statusResposta);
    }
}