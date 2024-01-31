<?php

namespace App\Controller;

use App\Entity\Goodies;
use App\Form\GoodiesType;
use App\Repository\GoodiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/goodies')]
class GoodiesController extends AbstractController
{
    #[Route('/', name: 'app_goodies_index', methods: ['GET'])]
    public function index(GoodiesRepository $goodiesRepository): Response
    {
        return $this->render('goodies/index.html.twig', [
            'goodies' => $goodiesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_goodies_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $goody = new Goodies();
        $form = $this->createForm(GoodiesType::class, $goody);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($goody);
            $entityManager->flush();

            return $this->redirectToRoute('app_goodies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('goodies/new.html.twig', [
            'goody' => $goody,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_goodies_show', methods: ['GET'])]
    public function show(Goodies $goody): Response
    {
        return $this->render('goodies/show.html.twig', [
            'goody' => $goody,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_goodies_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Goodies $goody, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GoodiesType::class, $goody);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_goodies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('goodies/edit.html.twig', [
            'goody' => $goody,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_goodies_delete', methods: ['POST'])]
    public function delete(Request $request, Goodies $goody, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$goody->getId(), $request->request->get('_token'))) {
            $entityManager->remove($goody);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_goodies_index', [], Response::HTTP_SEE_OTHER);
    }
}
