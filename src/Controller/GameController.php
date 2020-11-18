<?php

namespace App\Controller;

use App\Form\GameType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/game/new", name="app_game_new")
     */
    public function newAction(): Response
    {
        $form = $this->createForm(GameType::class);

        return $this->render('game/new.html.twig', ['form' => $form->createView()]);
    }
}
