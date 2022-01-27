<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Tag;
use App\Form\QuestionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/question", name="question_")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $questions = $this->getDoctrine()
            ->getRepository(Question::class)
            ->findAll();

        return $this->render(
            'question/index.html.twig',
            ['questions' => $questions]
        );
    }

    /**
     * @Route("/{id}", name="show")
     */
    public function show(Question $question): Response
    {
        return $this->render(
            'question/show.html.twig',
            ['question' => $question]
        );
    }

}
