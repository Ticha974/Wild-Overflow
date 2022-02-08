<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/myquestion", name="myquestion_")
 */
class MyQuestionController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $questions = $this->getDoctrine()->getRepository(Question::class)
            ->findBy(['user' => $user], ['createdAt' => 'DESC']);

        return $this->render(
            'myQuestion/index.html.twig',
            ['questions' => $questions]
        );
    }

    /**
     * @Route("/validateAnswer/{id}", name="validateAnswer", methods={"GET", "POST"})
     */
    public function validateAnswer(Answer $answer, EntityManagerInterface $entityManager): Response
    {
        $answer->setIsValid(true);
        $entityManager->flush();

        return $this->redirectToRoute('question_show', ['slug' => $answer->getQuestion()->getSlug()]);
    }
}
