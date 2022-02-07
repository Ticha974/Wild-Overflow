<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class Slugify
{
    private SluggerInterface $slugger;
    private QuestionRepository $questionRepository;

    public function __construct(SluggerInterface $slugger, QuestionRepository $questionRepository)
    {
        $this->slugger = $slugger;
        $this->questionRepository = $questionRepository;
    }

    public function findUniqueSlug(Question $question): string
    {
        $slug = $this->slugger->slug($question->getTitle())->toString();

        $questionWithSameSlug = $this->questionRepository->findBy([
            'slug' => $slug,
        ]);

        if (!$questionWithSameSlug) {
            return $slug;
        }
        $originalSlug = $slug;
        $increment = 1;
        do {
            $slug = $originalSlug . '-' . $increment;
            $increment++;

            $questionWithSameSlug = $this->questionRepository->findBy([
                'slug' => $slug,
            ]);
        } while (count($questionWithSameSlug) !== 0);
        return $slug;
    }
}
