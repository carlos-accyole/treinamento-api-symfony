<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

class ExtratorDadosRequest
{
    private function buscaDadosRequest(Request $request)
    {
        $queryString = $request->query->all();
        $dadosOrdenacao = array_key_exists('sort', $queryString)
            ? $queryString['sort']
            : [];
        unset($queryString['sort']);

        $paginaAtual = array_key_exists('page', $queryString)
            ? $queryString['page']
            : 1;
        unset($queryString['page']);

        $itensPorPagina = array_key_exists('itensPorPagina', $queryString)
            ? $queryString['itensPorPagina']
            : 5;
        unset($queryString['itensPorPagina']);

        return [$queryString, $dadosOrdenacao, $paginaAtual, $itensPorPagina];
    }

    public function buscaDadosOrdenacao(Request $request)
    {
        [,$informacoesDeOrdenacao] = $this->buscaDadosRequest($request);

        return $informacoesDeOrdenacao;
    }

    public function buscarDadosFiltro(Request $request)
    {
        [$insformacoesDeFiltro,] = $this->buscaDadosRequest($request);

        return $insformacoesDeFiltro;
    }

    public function buscarDadosPaginacao(Request $request)
    {
        [, , $paginaAtual, $itensPorPagina] = $this->buscaDadosRequest($request);

        return [$paginaAtual, $itensPorPagina];
    }
}