<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Handler\NewGameHandler;
use App\Manager\GameManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    /**
     * @Route("/game/new", name="app_game_new")
     */
    public function newAction(Request $request, NewGameHandler $newGameHandler, GameManager $gameManager): Response
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newGameHandler->setup($game, $form->get('creator')->getData(), $form->get('creatorColor')->getData());
            $gameManager->create($game);

            return $this->redirectToRoute('app_game_index', ['id' => $game->getId()]);
        }

        return $this->render('game/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/game/{id}", name="app_game_index")
     */
    public function indexAction(Game $game): Response
    {
        return $this->render('game/index.html.twig', ['game' => $game]);
    }
}
