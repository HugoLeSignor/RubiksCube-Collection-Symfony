<?php

namespace App\Controller;

use App\Repository\RubiksCubeRepository;
use App\Repository\UserCollectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        RubiksCubeRepository $cubeRepository,
        UserCollectionRepository $collectionRepository
    ): Response {
        $topRatedCubes = $cubeRepository->findTopRated(6);
        $recentCubes = $cubeRepository->findBy([], ['createdAt' => 'DESC'], 6);

        return $this->render('home/index.html.twig', [
            'topRatedCubes' => $topRatedCubes,
            'recentCubes' => $recentCubes,
        ]);
    }
}
