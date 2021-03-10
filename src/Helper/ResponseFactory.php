<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    /**
     * @var bool
     */
    private bool $sucesso;

    private $conteudoResposta;

    /**
     * @var int|null
     */
    private ?int $paginaAtual;

    /**
     * @var int|null
     */
    private ?int $itensPorPagina;

    /**
     * @var int
     */
    private int $statusReposta;


    /**
     * ResponseFactory constructor.
     * @param bool $sucesso
     * @param $conteudoResposta
     * @param int $statusReposta
     * @param int|null $paginaAtual
     * @param int|null $itensPorPagina
     */
    public function __construct(
        bool $sucesso,
        $conteudoResposta,
        int $statusReposta = Response::HTTP_OK,
        int $paginaAtual = null,
        int $itensPorPagina = null
    ){
        $this->sucesso = $sucesso;
        $this->conteudoResposta = $conteudoResposta;
        $this->paginaAtual = $paginaAtual;
        $this->itensPorPagina = $itensPorPagina;
        $this->statusReposta = $statusReposta;
    }

    public function getResponse(): JsonResponse
    {
        $this->conteudoResposta = [
            'sucesso' => $this->sucesso,
            'paginaAtual' => $this->paginaAtual,
            'itensPorPagina' => $this->itensPorPagina,
            'conteudoResposta' => $this->conteudoResposta

        ];

        if (is_null($this->paginaAtual)) {
            unset($this->conteudoResposta['paginaAtual']);
            unset($this->conteudoResposta['itensPorPagina']);
        }

        return new JsonResponse($this->conteudoResposta, $this->statusReposta);
    }
}