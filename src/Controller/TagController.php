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
 * @Route("/question/tag", name="tag_")
 */
class TagController extends AbstractController
{
    /**
     * @Route("/{slug}", name="question")
     */
    public function show(Tag $tag): Response
    {
        return $this->render(
            'tag/show.html.twig',
            ['tag' => $tag]
        );
    }
}
