<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\Vehicule;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// #[Route('/reservation')]
final class ReservationController extends AbstractController
{

    #[Route('/client/reservation', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/reservation/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reservation = new Reservation();
        $vehiculeId = $request->query->get('id');
        if ($vehiculeId) {
            $vehicule = $entityManager->getRepository(Vehicule::class)->find($vehiculeId);
            if ($vehicule) {
                $reservation->setVehicule($vehicule);
            }
        } else {
            $vehicule = null;
        }


        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData('client'));

            $client = $form->get('client')->getData();
            // dd($client);
            if (!$client) {
                $reservation->setClient($this->getUser());
                // dd($reservation);
            } else {
                $reservation->setClient($client);
            }

            $dateDebut = $reservation->getDateDebut();
            $dateFin = $reservation->getDateFin();
            $interval = $dateDebut->diff($dateFin);
            $prix_journalier = $reservation->getVehicule()->getPrixJournalier();
            $reservation->setPrixTotal($interval->days * $prix_journalier);
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->render('reservation/new.html.twig', [
                'reservation' => $reservation,
                'vehicule' => $vehicule ? $vehicule : null,
                'form' => $form,
            ]);
        }
    }

    #[Route('/admin/reservation/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/admin/reservation/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/admin/reservation/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
