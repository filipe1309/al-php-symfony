<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MedicosController extends AbstractController
{
    private $entityManager;
    private $medicoFactory;

    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $medicoFactory)
    {
        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function novo(Request $request): Response
    {
        $corpoRequisiscao = $request->getContent();
        $medico = $this->medicoFactory->criarMedico($corpoRequisiscao);

        // Entity observar esta entidade
        $this->entityManager->persist($medico);

        // Envia alteracoes para o banco
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function buscarTodos(): Response
    {
        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);
        $medicoList = $repositorioDeMedicos->findAll();

        return new JsonResponse($medicoList);
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
    public function buscarUm(int $id): Response
    {
        $medico = $this->buscaMedico($id);
        $codigoRetorno = is_null($medico) ? Response::HTTP_NO_CONTENT : Response::HTTP_OK;

        return new JsonResponse($medico, $codigoRetorno);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function atualiza(int $id, Request $request): Response
    {
        $corpoRequisiscao = $request->getContent();

        $medicoEnviado = $this->medicoFactory->criarMedico($corpoRequisiscao);

        $medicoExistente = $this->buscaMedico($id);

        if (is_null($medicoExistente)) {
            return new Response(null, Response::HTTP_NOT_FOUND);
        }

        $medicoExistente->crm = $medicoEnviado->crm;
        $medicoExistente->nome = $medicoEnviado->nome;

        // Envia alteracoes para o banco
        $this->entityManager->flush();

        return new JsonResponse($medicoExistente);
    }

    public function buscaMedico(int $id)
    {
        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);

        return $repositorioDeMedicos->find($id);
    }
}