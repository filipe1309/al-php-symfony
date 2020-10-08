<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseFactory
{
    private $sucesso;
    private $conteudoResposta;
    private $statusResposta;
    private $page;
    private $itemsPerPage;

    public function __construct(
        bool $sucesso,
        $conteudoResposta,
        int $statusResposta = Response::HTTP_OK,
        int $page = null,
        int $itemsPerPage = null
    ) {
        $this->sucesso = $sucesso;
        $this->conteudoResposta = $conteudoResposta;
        $this->statusResposta = $statusResposta;
        $this->page = $page;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getResponse(): JsonResponse
    {
        $conteudoResposta = [
            'sucesso' => $this->sucesso,
            'page' => $this->page,
            'itemsPerPage' => $this->itemsPerPage,
            'conteudoResposta' => $this->conteudoResposta,
            // TODO Add total de resultados
            // TODO Add URL da proxima pagina e anterior
        ];

        if (is_null($this->page)) {
            unset($conteudoResposta['page']);
            unset($conteudoResposta['itemsPerPage']);
        }

        return new JsonResponse($conteudoResposta, $this->statusResposta);
    }
}