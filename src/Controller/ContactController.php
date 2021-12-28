<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;
use Swift_Mailer;

class ContactController extends AbstractController
{
    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }


    public function notify(Contact $contact)
    {
        $message = (new \Swift_Message('tfc : ' . $contact->getNom().$contact->getPrenom()))
        ->setFrom(['tfc@example.com' => 'PresidentClub'])
        ->setTo('contact@example.com')
        ->setReplyTo($contact->getEmail())
        ->setBody($this->render('email/contact.html.twig', [
            'contact' => $contact
        ]), 'text/html');
    $this->mailer->send($message);

    }

    /**
     * @Route("/contact", name="contact")
     */

    public function index(Swift_Mailer $mailer, Request $request):Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->notify($contact);
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);

        }
        return $this->render('contact/index.html.twig', [
            'contact' => $contact,
            'form' => $form->createView()
        ]);

    } 
}
