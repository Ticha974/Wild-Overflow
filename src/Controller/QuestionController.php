<?php

namespace App\Controller;

use App\Entity\Question;

use App\Entity\Tag;
use App\Entity\User;
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
     * @Route("/new", name="new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['id' => 4]);
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
