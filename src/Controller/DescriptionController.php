<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DescriptionController extends AbstractController
{
    /**
     * @Route("/qui-sommes-nous", name="description")
     */
    public function index(): Response
    {
        return $this->render('description/index.html.twig');
    }
}
