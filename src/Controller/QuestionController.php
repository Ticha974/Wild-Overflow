<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Form\AnswerType;
use App\Entity\Question;
use App\Form\QuestionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

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
     * @Route("/latest", name="latest")
     */
    public function latest(): Response
    {
        $questions = $this->getDoctrine()
            ->getRepository(Question::class)
            ->findBy([], ['createdAt' => 'DESC']);
        return $this->render(
            'question/index.html.twig',
            ['questions' => $questions]
        );
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        if ($this->getUser()) {
            $question = new Question();
            $form = $this->createForm(QuestionType::class, $question);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $question->setUser($this->getUser());
                $slug = $slugger->slug($question->getTitle());
                $question->setSlug($slug);
                $entityManager->persist($question);
                $entityManager->flush();
                return $this->redirectToRoute('question_index');
            }
            return $this->render('question/new.html.twig', ["form" => $form->createView()]);
        }
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/{slug}", name="show")
     */
    public function show(Question $question, Request $request, EntityManagerInterface $entityManager): Response
    {
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $answer->setQuestion($question);
            $answer->setUser($this->getUser());
            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute('question_show', ['slug' => $question->getSlug()]);
        }
        return $this->render(
            'question/show.html.twig',
            ['question' => $question, 'form' => $form->createView()]
        );
    }
}
