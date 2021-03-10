<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Helper\EspecialidadeFatory;
use App\Helper\ExtratorDadosRequest;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;

class EspecialidadesController extends BaseController
{
    public function __construct(
        EntityManagerInterface $entityManager,
        EspecialidadeRepository $especialidadeRepository,
        EspecialidadeFatory $especialidadeFatory,
        ExtratorDadosRequest $extratorDadosRequest
    ){
        parent::__construct($especialidadeRepository, $entityManager, $especialidadeFatory, $extratorDadosRequest);
    }

    /**
     * @param Especialidade $entidadeExistente
     * @param Especialidade $entidadeEnviada
     */
    public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada)
    {
        if (is_null($entidadeExistente)) {
            throw new \InvalidArgumentException();
        }

        $entidadeExistente
            ->setDescricao($entidadeEnviada->getDescricao()
        );
    }
}
