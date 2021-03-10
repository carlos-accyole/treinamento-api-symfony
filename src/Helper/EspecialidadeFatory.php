<?php

namespace App\Helper;

use App\Entity\Especialidade;

class EspecialidadeFatory implements EntidadeFactory
{
    /**
     * @param string $json
     * @return Especialidade
     */
    public function criarEntidade(string $json): Especialidade
    {
        $dadosJson = json_decode($json);
        $especialidade = new Especialidade();
        $especialidade->setDescricao($dadosJson->descricao);

        return $especialidade;
    }
}