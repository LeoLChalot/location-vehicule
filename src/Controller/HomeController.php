<?php

namespace App\Controller;

use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(VehiculeRepository $vehiculeRepository): Response
    {
        $vehicules = $vehiculeRepository->findAll();

        return $this->render('home/index.html.twig', [
            'vehicules' => $vehicules,
        ]);
    }

    #[Route('/catalogue', name: 'app_home_catalogue')]
    public function catalogue(VehiculeRepository $vehiculeRepository): Response
    {
        return $this->render('home/catalogue.html.twig', [
            'vehicules' => $vehiculeRepository->findAll(),
        ]);
    }
}
