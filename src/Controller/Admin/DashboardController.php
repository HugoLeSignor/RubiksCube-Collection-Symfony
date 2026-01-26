<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Rating;
use App\Entity\RubiksCube;
use App\Entity\User;
use App\Entity\UserCollection;
use App\Repository\CommentRepository;
use App\Repository\RatingRepository;
use App\Repository\RubiksCubeRepository;
use App\Repository\UserCollectionRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private RubiksCubeRepository $cubeRepository,
        private UserRepository $userRepository,
        private CommentRepository $commentRepository,
        private RatingRepository $ratingRepository,
        private UserCollectionRepository $collectionRepository
    ) {
    }

    public function index(): Response
    {
        $totalCubes = $this->cubeRepository->count([]);
        $totalUsers = $this->userRepository->count([]);
        $totalComments = $this->commentRepository->count([]);
        $totalRatings = $this->ratingRepository->count([]);
        $totalCollections = $this->collectionRepository->count([]);

        $recentCubes = $this->cubeRepository->findBy([], ['createdAt' => 'DESC'], 5);
        $recentComments = $this->commentRepository->findBy([], ['createdAt' => 'DESC'], 5);
        $recentUsers = $this->userRepository->findBy([], ['createdAt' => 'DESC'], 5);

        return $this->render('admin/dashboard.html.twig', [
            'totalCubes' => $totalCubes,
            'totalUsers' => $totalUsers,
            'totalComments' => $totalComments,
            'totalRatings' => $totalRatings,
            'totalCollections' => $totalCollections,
            'recentCubes' => $recentCubes,
            'recentComments' => $recentComments,
            'recentUsers' => $recentUsers,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('🧩 Collection Admin')
            ->setFaviconPath('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 128 128%22><text y=%221.2em%22 font-size=%2296%22>🧩</text></svg>');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('📦 Gestion des Produits');
        yield MenuItem::linkToCrud('Rubik\'s Cubes', 'fa fa-cube', RubiksCube::class);
        yield MenuItem::linkToCrud('Collections Utilisateurs', 'fa fa-box-open', UserCollection::class);

        yield MenuItem::section('💬 Interactions');
        yield MenuItem::linkToCrud('Commentaires', 'fa fa-comments', Comment::class);
        yield MenuItem::linkToCrud('Notes', 'fa fa-star', Rating::class);

        yield MenuItem::section('👥 Utilisateurs');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', User::class);

        yield MenuItem::section('🌐 Retour au site');
        yield MenuItem::linkToRoute('Voir le site', 'fa fa-home', 'app_home');
    }
}
