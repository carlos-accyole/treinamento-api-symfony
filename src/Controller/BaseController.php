<?php

namespace App\Controller;

use App\Helper\EntidadeFactory;
use App\Helper\ExtratorDadosRequest;
use App\Helper\ResponseFactory;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class BaseController extends AbstractController
{

    /**
     * @var ObjectRepository
     */
    protected ObjectRepository $repository;

    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * @var EntidadeFactory
     */
    protected EntidadeFactory $entidadeFactory;
    /**
     * @var ExtratorDadosRequest
     */
    protected ExtratorDadosRequest $extratorDadosRequest;

    public function __construct(
        ObjectRepository $repository,
        EntityManagerInterface $entityManager,
        EntidadeFactory $entidadeFactory,
        ExtratorDadosRequest $extratorDadosRequest
    ){
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->entidadeFactory = $entidadeFactory;
        $this->extratorDadosRequest = $extratorDadosRequest;
    }

    public function novo(Request $request): Response
    {
        $dadosRequest = $request->getContent();
        $entidade = $this->entidadeFactory->criarEntidade($dadosRequest);
        $this->entityManager->persist($entidade);
        $this->entityManager->flush();

        return new JsonResponse($entidade);
    }

    public function buscarTodos(Request $request): Response
    {
        $informacoesDeOrdenacao = $this->extratorDadosRequest->buscaDadosOrdenacao($request);
        $filtro = $this->extratorDadosRequest->buscarDadosFiltro($request);
        [$paginaAtual, $itensPorPagina] = $this->extratorDadosRequest->buscarDadosPaginacao($request);

//        dump($informacoesDeOrdenacao);
//        dump($filtro);
//        dump([$paginaAtual, $itensPorPagina]);die;

        $lista = $this->repository->findBy(
            $filtro,
            $informacoesDeOrdenacao,
            $itensPorPagina,
            ($paginaAtual - 1) * $itensPorPagina
        );

        $fabricaDeRespostas = new ResponseFactory(true, $lista, Response::HTTP_OK, $paginaAtual, $itensPorPagina);

        return $fabricaDeRespostas->getResponse();
    }

    public function buscarUm(int $id): Response
    {
        $entidade = $this->repository->find($id);
        $statusResposta = is_null($entidade) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;
        $fabricaDeRespostas = new ResponseFactory(true, $entidade, $statusResposta);

        return $fabricaDeRespostas->getResponse();
    }

    public function atualiza(int $id, Request $request): Response
    {

        $corpoRequisicao = $request->getContent();
        $entidadeEnviada = $this->entidadeFactory->criarEntidade($corpoRequisicao);

        try {
            $entidadeExistente = $this->repository->find($id);

            $this->atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);

            $this->entityManager->flush();

            $fabrica = new ResponseFactory(true, $entidadeExistente, Response::HTTP_OK);

            return $fabrica->getResponse();
        } catch (\InvalidArgumentException $exception) {
            $fabrica = new ResponseFactory(false, 'Recurso não encontrado', Response::HTTP_NOT_FOUND);

            return $fabrica->getResponse();
        }
    }

    public function remove(int $id): Response
    {
        $entidade = $this->repository->find($id);
        $this->entityManager->remove($entidade);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    abstract public function atualizarEntidadeExistente($entidadeExistente, $entidadeEnviada);
}