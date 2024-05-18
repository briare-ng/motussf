<?php

namespace App\Controller;

use App\Entity\Scores;
use App\Form\ScoresType;
use App\Repository\ScoresRepository;
use App\Repository\DictionaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/scores')]
class ScoresController extends AbstractController
{
    #[Route('/', name: 'app_scores_index', methods: ['GET'])]
    public function index(ScoresRepository $scoresRepository): Response
    {
        return $this->render('scores/index.html.twig', [
            'titre' => 'Les 20 meilleurs scores',
            'scores' => $scoresRepository->findBy([], ['points' => 'DESC'], 20),
            'titre2' => 'Les 10 meilleurs joueurs',
            'TopPlayers' => $scoresRepository->findTopPlayers(),
        ]);
    }

    #[Route('/mesScores', name: 'app_user_scores', methods: ['GET'])]
    public function userScores(ScoresRepository $scoresRepository): Response
    {
        return $this->render('scores/show.html.twig', [
            'titre' => 'Mes scores',
            'scores' => $scoresRepository->findBy(['user' => $this->getUser()], ['points' => 'DESC'], 20),
        ]);
    }

    // #[Route('/new', name: 'app_scores_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $score = new Scores();
    //     $form = $this->createForm(ScoresType::class, $score);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($score);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_scores_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('scores/new.html.twig', [
    //         'score' => $score,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_scores_show', methods: ['GET'])]
    // public function show(Scores $score): Response
    // {
    //     return $this->render('scores/show.html.twig', [
    //         'score' => $score,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_scores_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Scores $score, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(ScoresType::class, $score);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_scores_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('scores/edit.html.twig', [
    //         'score' => $score,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_scores_delete', methods: ['POST'])]
    // public function delete(Request $request, Scores $score, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $score->getId(), $request->getPayload()->get('_token'))) {
    //         $entityManager->remove($score);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_scores_index', [], Response::HTTP_SEE_OTHER);
    // }


    /**
     * ajoute un score en base de données
     */
    #[Route('/addScore/{points}/{word_id}', name: 'app_scores_addScore', methods: ['GET', 'POST'])]
    public function addScore(int $points, $word_id, EntityManagerInterface $entityManager, DictionaryRepository $dictionaryRepo): Response
    {
        // Récupérer l'entité Dictionary correspondante
        $word = $dictionaryRepo->find($word_id);
        if (!$word) {
            throw $this->createNotFoundException('Aucun mot trouvé pour cet id : ' . $word_id);
        }
        $score = new Scores();
        //ajouter les méthodes d'accès aux propriétés de la classe (setters)
        $score->setPoints($points);
        $score->setDateTime(new \DateTimeImmutable());
        $score->setWord($word);
        $score->setUser($this->getUser());
        $entityManager->persist($score);
        $entityManager->flush();

        //TODO retour à modifier
        return new Response('score successfully added to DB!');
    }
}
