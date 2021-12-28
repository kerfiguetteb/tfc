<?php

namespace App\Controller;

use App\Entity\Domicile;
use App\Form\DomicileType;
use App\Repository\DomicileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/domicile")
 */
class DomicileController extends AbstractController
{
    /**
     * @Route("/", name="domicile_index", methods={"GET"})
     */
    public function index(DomicileRepository $domicileRepository): Response
    {
        return $this->render('domicile/index.html.twig', [
            'domiciles' => $domicileRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="domicile_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $domicile = new Domicile();
        $form = $this->createForm(DomicileType::class, $domicile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($domicile);
            $entityManager->flush();

            return $this->redirectToRoute('domicile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('domicile/new.html.twig', [
            'domicile' => $domicile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="domicile_show", methods={"GET"})
     */
    public function show(Domicile $domicile): Response
    {
        return $this->render('domicile/show.html.twig', [
            'domicile' => $domicile,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="domicile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Domicile $domicile): Response
    {
        $form = $this->createForm(DomicileType::class, $domicile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

       }

        return $this->render('domicile/edit.html.twig', [
            'domicile' => $domicile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="domicile_delete", methods={"POST"})
     */
    public function delete(Request $request, Domicile $domicile): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domicile->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($domicile);
            $entityManager->flush();
        }

        return $this->redirectToRoute('domicile_index', [], Response::HTTP_SEE_OTHER);
    }
}
