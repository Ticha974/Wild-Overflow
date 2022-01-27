<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\User;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/question", name="question_")
 */

class QuestionController extends AbstractController
{
    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => 3]);

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setUser($user);
            $entityManager->persist($question);
            $entityManager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('question/new.html.twig', ["form" => $form->createView()]);
    }
}
