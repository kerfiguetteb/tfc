<?php

namespace App\Controller;

use App\Entity\Entraineur;
use App\Form\EntraineurType;
use App\Repository\EntraineurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entraineur")
 */
class EntraineurController extends AbstractController
{
    /**
     * @Route("/", name="entraineur_index", methods={"GET"})
     */
    public function index(EntraineurRepository $entraineurRepository): Response
    {
        return $this->render('entraineur/index.html.twig', [
            'entraineurs' => $entraineurRepository->findAll(),
        ]);
    }

    // /**
    //  * @Route("/new", name="entraineur_new", methods={"GET","POST"})
    //  */
    // public function new(Request $request): Response
    // {
    //     $entraineur = new Entraineur();
    //     $form = $this->createForm(EntraineurType::class, $entraineur);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($entraineur);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('entraineur_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('entraineur/new.html.twig', [
    //         'entraineur' => $entraineur,
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/{id}", name="entraineur_show", methods={"GET"})
     */
    public function show(Entraineur $entraineur): Response
    {
        return $this->render('entraineur/show.html.twig', [
            'entraineur' => $entraineur,
        ]);
    }


}


