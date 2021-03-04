<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(SessionInterface $session): Response
    {
        $session->remove('cart');

        $cart = $session->get('cart');

        $mail = new Mail();
        $mail->send('ionisbry@gmail.com', 'Yoka', 'Mon premier mail', 'Mon premier super mail de folie dingue dingue !');
        
        return $this->render('home/index.html.twig');
    }
}
