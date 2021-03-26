<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Header;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(SessionInterface $session, Cart $cart): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);
        $hearders = $this->entityManager->getRepository(Header::class)->findAll();

        // $session->remove('cart');

        // $cart = $session->get('cart');

        // $mail = new Mail();
        // $mail->send('ionisbry@gmail.com', 'Yoka', 'Mon premier mail', 'Mon premier super mail de folie dingue dingue !');
        
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'headers' => $hearders,
        ]);
    }
}
