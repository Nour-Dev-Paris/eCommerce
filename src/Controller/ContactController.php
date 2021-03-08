<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/nous-contacter", name="contact")
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()  && $form->isValid()) {
            $this->addFlash('notice', 'Merci de nous a voir contacté. Notre équipe va vous répondre dans les plus brefs délais.');

            // dd($name);
            
            $mail = new Mail();

            $content = "Vous avez reçu un message de la boutique eCommerce de la part de <br>" . $form->get('prenom')->getData() . ' ' . $form->get('nom')->getData() . ' : <br>' . $form->get('content')->getData();

            $name = $form->get('prenom')->getData() . ' ' . $form->get('nom')->getData();

            $mail->send(
                'abdenour-belkacemi@hotmail.fr', 
                'La boutique eCommerce', 
                'Message reçu de la boutique eCommerce - ' . $name,
                $content
            );
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
