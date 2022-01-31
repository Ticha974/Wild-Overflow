<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Question;
use App\Entity\Tag;
use App\Entity\User;

class UserController extends AbstractController
{
    /**
     * @Route("/{id}", name="user")
     */
    public function index(): Response
    {
        $user = new User();

        return $this->render(
            'user/user.html.twig',
            ['user' =>  $user]
        );
    }
}
