<?php

namespace App\Controller;

use App\Entity\Dictionary;
use App\Form\DictionaryType;
use App\Repository\DictionaryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dictionary')]
class DictionaryController extends AbstractController
{
    #[Route('/', name: 'app_dictionary_index', methods: ['GET'])]
    public function index(DictionaryRepository $dictionaryRepository): Response
    {
        return $this->render('dictionary/index.html.twig', [
            'dictionaries' => $dictionaryRepository->findAll(),
        ]);
    }

    // #[Route('/new', name: 'app_dictionary_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $dictionary = new Dictionary();
    //     $form = $this->createForm(DictionaryType::class, $dictionary);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($dictionary);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_dictionary_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('dictionary/new.html.twig', [
    //         'dictionary' => $dictionary,
    //         'form' => $form,
    //     ]);
    // }

    /**
     * récupération d'un jeu de mots d'une longueur sélectionnée $selectedNumber passée en GET 
     *
     * @param int $selectedNumber : number of letter selected for the secret word
     * @param DictionaryRepository $dictionaryRepository
     * @return JsonResponse
     */
    #[Route('/jsonQuery/{selectedNumber}', name: 'app_dictionary_jsonQuery', methods: ['GET'])]
    public function jsonQuery(DictionaryRepository $dictionaryRepository, int $selectedNumber): JsonResponse
    {
        $results = $dictionaryRepository->findBy(["numberLetters" => $selectedNumber]);
        $words = array();
        // dd($results);
        foreach ($results as $result) {
            $word = $result->getWord();
            $wordID = $result->getId();
            $words[$wordID]= $word;
        }
        return new JsonResponse($words);
    }
    // #[Route('/{id}', name: 'app_dictionary_show', methods: ['GET'])]
    // public function show(Dictionary $dictionary): Response
    // {
    //     return $this->render('dictionary/show.html.twig', [
    //         'dictionary' => $dictionary,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_dictionary_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Dictionary $dictionary, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(DictionaryType::class, $dictionary);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_dictionary_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('dictionary/edit.html.twig', [
    //         'dictionary' => $dictionary,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_dictionary_delete', methods: ['POST'])]
    // public function delete(Request $request, Dictionary $dictionary, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $dictionary->getId(), $request->getPayload()->get('_token'))) {
    //         $entityManager->remove($dictionary);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_dictionary_index', [], Response::HTTP_SEE_OTHER);
    // }
}
