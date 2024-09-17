<?php

namespace App\Controller;

use App\Entity\Incident;
use App\Entity\Status;
use App\Form\IncidentType;
use App\Repository\IncidentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/incident')]
final class IncidentController extends AbstractController{
    #[Route(name: 'app_incident_index', methods: ['GET'])]
    public function index(IncidentRepository $incidentRepository): Response
    {
        return $this->render('incident/index.html.twig', [
            'incidents' => $incidentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_incident_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $incident = new Incident();
        $form = $this->createForm(IncidentType::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($incident);
            $entityManager->flush();

            return $this->redirectToRoute('app_incident_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('incident/new.html.twig', [
            'incident' => $incident,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_incident_show', methods: ['GET'])]
    public function show(Incident $incident): Response
    {
        return $this->render('incident/show.html.twig', [
            'incident' => $incident,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_incident_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Incident $incident, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IncidentType::class, $incident);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_incident_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('incident/edit.html.twig', [
            'incident' => $incident,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_incident_delete', methods: ['POST'])]
    public function delete(Request $request, Incident $incident, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$incident->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($incident);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_incident_index', [], Response::HTTP_SEE_OTHER);
    }
#[Route('/{id}/process', name: 'app_incident_process', methods: ['GET'])]

    public function process(Incident $incident, EntityManagerInterface $entityManager): Response
    {
        //dd permet de voir le bug exemple dd($incident);
        $incident->setProcessedAt(new \DateTimeImmutable());
        $incident->setStatus(Status::IN_PROGRESS);
        $entityManager->flush();

        return $this->redirectToRoute('app_incident_show', ['id'=>$incident->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/resolve', name: 'app_incident_resolve', methods: ['GET'])]

    public function resolve(Incident $incident, EntityManagerInterface $entityManager): Response
    {
        $incident->setResolvedAt(new \DateTimeImmutable());
        $incident->setStatus(Status::RESOLVED);
        $entityManager->flush();

        return $this->redirectToRoute('app_incident_show', ['id'=>$incident->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/reject', name: 'app_incident_reject', methods: ['GET'])]

    public function reject(Incident $incident, EntityManagerInterface $entityManager): Response
    {
        $incident->setRejectedAt(new \DateTimeImmutable());
        $incident->setStatus(Status::REJECTED);
        $entityManager->flush();

        return $this->redirectToRoute('app_incident_show', ['id'=>$incident->getId()], Response::HTTP_SEE_OTHER);
    }
}
