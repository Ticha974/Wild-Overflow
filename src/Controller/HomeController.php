<?php

namespace App\Controller;

use App\Form\SearchQuestionFormType;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tag;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request, QuestionRepository $questionRepository): Response
    {
        $form = $this->createForm(SearchQuestionFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()['search'];
            $questions = $questionRepository->findLikeName($search);

            return $this->render('question/index.html.twig', ['questions' => $questions,]);

        } else {
            $questions = $questionRepository->findAll();
        }

        $tag = $this->getDoctrine()
            ->getRepository(tag::class)
            ->findAll();

        return $this->render('home/home.html.twig', [
            'tag' => $tag,
            'questions' => $questions,
            'form' => $form->createView(),
            ]);
    }
}
