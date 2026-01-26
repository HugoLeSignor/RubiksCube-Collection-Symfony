<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Rating;
use App\Entity\RubiksCube;
use App\Repository\CommentRepository;
use App\Repository\RatingRepository;
use App\Repository\RubiksCubeRepository;
use App\Service\CubeTypeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rubiks-cube')]
class RubiksCubeController extends AbstractController
{
    #[Route('/', name: 'app_rubiks_cube_index')]
    public function index(
        Request $request,
        RubiksCubeRepository $cubeRepository,
        CubeTypeService $cubeTypeService
    ): Response {
        $search = $request->query->get('search', '');
        $type = $request->query->get('type', '');

        if ($search) {
            $cubes = $cubeRepository->searchByNameOrBrand($search);
        } elseif ($type) {
            $cubes = $cubeRepository->findByType($type);
        } else {
            $cubes = $cubeRepository->findBy([], ['name' => 'ASC']);
        }

        return $this->render('rubiks_cube/index.html.twig', [
            'cubes' => $cubes,
            'types' => $cubeTypeService->getCubeTypes(),
            'currentType' => $type,
            'currentSearch' => $search,
        ]);
    }

    #[Route('/new', name: 'app_rubiks_cube_new')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        CubeTypeService $cubeTypeService
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($request->isMethod('POST')) {
            $cube = new RubiksCube();
            $cube->setName($request->request->get('name'));
            $cube->setType($request->request->get('type'));
            $cube->setDescription($request->request->get('description'));
            $cube->setBrand($request->request->get('brand'));
            $cube->setImageUrl($request->request->get('imageUrl'));
            $cube->setReleaseYear((int) $request->request->get('releaseYear') ?: null);
            $cube->setDifficulty($request->request->get('difficulty'));

            $em->persist($cube);
            $em->flush();

            $this->addFlash('success', 'Le Rubik\'s Cube a été ajouté avec succès !');
            return $this->redirectToRoute('app_rubiks_cube_show', ['id' => $cube->getId()]);
        }

        return $this->render('rubiks_cube/new.html.twig', [
            'types' => $cubeTypeService->getCubeTypes(),
            'difficulties' => $cubeTypeService->getDifficulties(),
        ]);
    }

    #[Route('/{id}', name: 'app_rubiks_cube_show', requirements: ['id' => '\d+'])]
    public function show(
        RubiksCube $cube,
        CommentRepository $commentRepository,
        RatingRepository $ratingRepository
    ): Response {
        $comments = $commentRepository->findByRubiksCube($cube->getId());

        $userRating = null;
        if ($this->getUser()) {
            $userRating = $ratingRepository->findUserRatingForCube(
                $this->getUser()->getId(),
                $cube->getId()
            );
        }

        return $this->render('rubiks_cube/show.html.twig', [
            'cube' => $cube,
            'comments' => $comments,
            'userRating' => $userRating,
            'averageRating' => $cube->getAverageRating(),
        ]);
    }

    #[Route('/{id}/comment', name: 'app_rubiks_cube_comment', methods: ['POST'])]
    public function addComment(
        RubiksCube $cube,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $content = $request->request->get('content');

        if (empty(trim($content))) {
            $this->addFlash('error', 'Le commentaire ne peut pas être vide.');
            return $this->redirectToRoute('app_rubiks_cube_show', ['id' => $cube->getId()]);
        }

        $comment = new Comment();
        $comment->setUser($this->getUser());
        $comment->setRubiksCube($cube);
        $comment->setContent($content);

        $em->persist($comment);
        $em->flush();

        $this->addFlash('success', 'Votre commentaire a été ajouté !');
        return $this->redirectToRoute('app_rubiks_cube_show', ['id' => $cube->getId()]);
    }

    #[Route('/{id}/rate', name: 'app_rubiks_cube_rate', methods: ['POST'])]
    public function addRating(
        RubiksCube $cube,
        Request $request,
        EntityManagerInterface $em,
        RatingRepository $ratingRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $ratingValue = (int) $request->request->get('rating');

        if ($ratingValue < 1 || $ratingValue > 5) {
            $this->addFlash('error', 'La note doit être entre 1 et 5.');
            return $this->redirectToRoute('app_rubiks_cube_show', ['id' => $cube->getId()]);
        }

        $existingRating = $ratingRepository->findUserRatingForCube(
            $this->getUser()->getId(),
            $cube->getId()
        );

        if ($existingRating) {
            $existingRating->setRating($ratingValue);
            $this->addFlash('success', 'Votre note a été mise à jour !');
        } else {
            $rating = new Rating();
            $rating->setUser($this->getUser());
            $rating->setRubiksCube($cube);
            $rating->setRating($ratingValue);
            $em->persist($rating);
            $this->addFlash('success', 'Votre note a été ajoutée !');
        }

        $em->flush();

        return $this->redirectToRoute('app_rubiks_cube_show', ['id' => $cube->getId()]);
    }
}
