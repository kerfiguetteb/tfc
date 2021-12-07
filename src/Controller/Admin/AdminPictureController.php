<?php

namespace App\Controller\Admin;

use App\Entity\Picture;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


// /**
//  * @Route("/admin/post")
//  */

class AdminPictureController extends AbstractController{

    /**
     * @Route("admin/post/edit/{id}", name="admin.picture.delete", methods="DELETE" )
     */
    public function delete(Request $request, Picture $picture): Response
    {
        $postId = $picture->getPost()->getId();
        if ($this->isCsrfTokenValid('delete'.$picture->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($picture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin.post.edit', ['id'=>$postId], Response::HTTP_SEE_OTHER);
    }
}
