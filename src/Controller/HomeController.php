<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tag;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $tag = $this->getDoctrine()
            ->getRepository(tag::class)
            ->findAll();

        return $this->render('home/home.html.twig', [
            'tag' => $tag,
           ]);
    }
}
