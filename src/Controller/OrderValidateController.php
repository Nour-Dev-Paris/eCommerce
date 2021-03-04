<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderValidateController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
     */
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() != $this->getUser()) {
            $this->redirectToRoute('home');
        }

        if (!$order->getIsPaid()) {

            // Vider la session Cart
            $cart->remove();

            // Modifier le statut isPaid de notre commande en mettant à 1
            $order->setIsPaid(1);
            $this->entityManager->flush();
            
            // Envoyer un email à un client pour confirmer la commande

            $mail = new Mail();
            $content = "Bonjour ". $order->getUser()->getFirstname(). "<br>" . "Merci pour votre commande !";
            $mail->send(
                $order->getUser()->getEmail(), 
                $order->getUser()->getFirstname(), 
                "Votre commande numéro : " . $order->getReference() . " eCommerce - Symfony", 
                $content
            );
        }


        // Afficher les informations de la commande de l'utilisateur

        return $this->render('order_validate/index.html.twig', [
            'order' => $order
        ]);
    }
}
