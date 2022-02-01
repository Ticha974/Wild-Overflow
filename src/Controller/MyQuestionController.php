<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
            ->findBy(['user' => $user]);

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
        if ($answer->getQuestion()->isValidated($answer->getQuestion()->getAnswers())) {
            foreach ($answer->getQuestion()->getAnswers() as $ans) {
                if ($ans->getIsValid() == true) {
                    $ans->setIsValid(false);
                    $entityManager->persist($answer);
                    break;
                }
            }
        }

        $answer->setIsValid(true);
        $entityManager->persist($answer);
        $entityManager->flush();

        return $this->redirectToRoute('question_show', ['slug' => $answer->getQuestion()->getSlug()]);
    }
}
