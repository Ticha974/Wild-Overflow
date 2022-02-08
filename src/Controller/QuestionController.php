<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Form\QuestionType;
use App\Form\SearchQuestionFormType;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Slugify;

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
        return $this->render('question/index.html.twig', [
                'questions' => $questions,
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request, QuestionRepository $questionRepository): Response
    {
        $searchTerm = $request->query->get('search-term');
        $questions = $questionRepository->findLikeName($searchTerm);
        return $this->render('question/search.html.twig', ['questions' => $questions]);
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
     * @Route("/ask-question", name="new")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param Slugify $slugger
     * @return Response
     */
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        Slugify $slugger
    ): Response {
        $question = new Question();
        if ($this->getUser()) {
            $question = new Question();
            $form = $this->createForm(QuestionType::class, $question);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $question->setUser($this->getUser());
                $slug = $slugger->findUniqueSlug($question);
                $question->setSlug($slug);
                $entityManager->persist($question);
                $entityManager->flush();

                $this->addFlash('success', 'Your question has been submitted');
                return $this->redirectToRoute('question_new');
            }
            return $this->render(
                'question/new.html.twig',
                ["form" => $form->createView()]
            );
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

    /**
     * @Route("/{slug}/edit", name= "edit", methods={"GET", "POST"})
     */

    public function edit(
        Request $request,
        Question $question,
        EntityManagerInterface $entityManager
    ): Response {

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Your question has been successfully edited');
            return $this->redirectToRoute('myquestion_index');
        }

        return $this->renderForm('question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }
}
