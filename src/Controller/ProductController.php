<?php

namespace App\Controller;

use DateTime;
use App\Classe\Search;
use App\Entity\Comment;
use App\Entity\Product;
use App\Form\SearchType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $entityManger;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/nos-produits/", name="products")
     */
    public function index(Request $request): Response
    { 
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->entityManager->getRepository(Product::class)->findWidthSearch($search);
        } else {
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function show($slug, Request $request): Response
    {

        $comment = new Comment();

        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);

        $products = $this->entityManager->getRepository(Product::class)->findByIsBest(1);

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTime();
            
            $comment->setAuthor($this->getUser())
                    ->setProduct($product)
                    ->setCreatedAt($date);
            
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->addFlash('notice', 'Votre commentaire a bien été ajouté !');
        }
        
        if (!$product) {
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'products' => $products,
            'form' => $form->createView()
        ]);
    }
}
