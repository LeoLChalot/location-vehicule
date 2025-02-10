<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Vehicule;
use App\Form\ClientType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/client')]
final class ClientController extends AbstractController
{
    #[Route('/profil', name: 'app_client_profil')]
    public function index(UserRepository $userRepository): Response
    {

        $user = $userRepository->find($this->getUser());
        return $this->render('client/index.html.twig', [
            'user' => $user,
        ]);
    }
    
    #[Route('/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserRepository $userRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $userRepository->find($this->getUser());
        $form = $this->createForm(ClientType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('imageFile')->getData();
            if ($file) {
                $user->setImageFile($file);
            }

            // dd($user);

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('app_client_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/book/{id}', name:'app_client_book', methods: ['POST'])]
    public function reserve(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $vehicule = $entityManager->getRepository(Vehicule::class)->find($id);

        if (!$vehicule) {
            throw $this->createNotFoundException('The vehicle does not exist');
        }

        $existingReservation = $entityManager->getRepository(Reservation::class)->findOneBy([
            'vehicule' => $vehicule,
            'dateFin' => ['>', new \DateTime()]
        ]);

        if ($existingReservation) {
            $this->addFlash('error', 'The vehicle is already reserved.');
            return $this->redirectToRoute('app_client_profil', [], Response::HTTP_SEE_OTHER);
        }

        $reservation = new Reservation();
        $reservation->setClient($user);
        $reservation->setVehicule($vehicule);
        $reservation->setDateDebut(new \DateTime());
        $reservation->setDateFin((new \DateTime())->modify('+1 day'));

        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->redirectToRoute('app_client_profil', [], Response::HTTP_SEE_OTHER);

        return $this->render('client/edit.html.twig', [
            'client' => $user,
            'form' => $form,
        ]);
    }
}
