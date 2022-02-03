<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
use App\Entity\Tag;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {

        return $this->render(
            'home/index.html.twig'
        );
    }
    /**
     * @Route("/", name="tags")
     */
    public function show(): Response
    {
        $questions = $this->getDoctrine()
            ->getRepository(tag::class)
            ->findAll();
        return $this->render(
            'home/home.html.twig',
            ['tag' => $questions]
        );
    }
}
