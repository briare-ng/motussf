<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        //si pas connecté redirect vers {{path('app_login')}}
        if (!($this->getUser())) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('main/index.html.twig', [
            'titre' => 'Accueil Motus',
        ]);
    }

    // #[Route('/mes_scores', name: 'mes_scores')]
    // public function scores(): Response
    // {
    //     if (!($this->getUser())) {
    //         return $this->redirectToRoute('app_login');
    //     }
    //     return $this->render('main/mes_scores.html.twig', [
    //         'titre' => 'Mes scores',
    //     ]);
    // }

    // #[Route('/WallOfFame', name: 'wallOfFame')]
    // public function wallOfFame(): Response
    // {
    //     if (!($this->getUser())) {
    //         return $this->redirectToRoute('app_login');
    //     }
    //     return $this->render('main/scores.html.twig', [
    //         'titre' => 'Le "Wall Of Fame"',
    //     ]);
    // }
}
