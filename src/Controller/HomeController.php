<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\PictureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator, PostRepository $postRepository,PictureRepository $pictureRepository, Request $request): Response
    {   
        $pictures = $pictureRepository->findAll();  
        $qb = $postRepository->findBy([],['id' => 'DESC']);;

        $pagination = $paginator->paginate(
            $qb, /* query builder NOT result */
            $request->query->getInt('page', 1), /*page number*/
            4/*limit per page*/
        );

        return $this->render('home/index.html.twig', [
            'posts' => $pagination,
            'pictures' => $pictures
        ]);
    }
}
