<?php

namespace App\Controller;

use App\Entity\Rencontre;
use App\Form\RencontreType;
use App\Repository\RencontreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/rencontre")
 */
class RencontreController extends AbstractController
{
    /**
     * @Route("/", name="rencontre_index", methods={"GET"})
     */
    public function index(RencontreRepository $rencontreRepository): Response
    {
        return $this->render('rencontre/index.html.twig', [
            'rencontres' => $rencontreRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="rencontre_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rencontre = new Rencontre();
        $form = $this->createForm(RencontreType::class, $rencontre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rencontre);
            $entityManager->flush();

            return $this->redirectToRoute('rencontre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rencontre/new.html.twig', [
            'rencontre' => $rencontre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rencontre_show", methods={"GET"})
     */
    public function show(Rencontre $rencontre): Response
    {
        return $this->render('rencontre/show.html.twig', [
            'rencontre' => $rencontre,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="rencontre_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Rencontre $rencontre): Response
    {
        $form = $this->createForm(RencontreType::class, $rencontre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rencontre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rencontre/edit.html.twig', [
            'rencontre' => $rencontre,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="rencontre_delete", methods={"POST"})
     */
    public function delete(Request $request, Rencontre $rencontre): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rencontre->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($rencontre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('rencontre_index', [], Response::HTTP_SEE_OTHER);
    }
}
