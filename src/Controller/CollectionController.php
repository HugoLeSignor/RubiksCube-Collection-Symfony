<?php

namespace App\Controller;

use App\Entity\UserCollection;
use App\Repository\RubiksCubeRepository;
use App\Repository\UserCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/collection')]
class CollectionController extends AbstractController
{
    #[Route('/', name: 'app_collection_index')]
    public function index(UserCollectionRepository $collectionRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $collections = $collectionRepository->findByUser($this->getUser()->getId());

        return $this->render('collection/index.html.twig', [
            'collections' => $collections,
        ]);
    }

    #[Route('/add/{cubeId}', name: 'app_collection_add', methods: ['POST'])]
    public function add(
        int $cubeId,
        Request $request,
        EntityManagerInterface $em,
        RubiksCubeRepository $cubeRepository,
        UserCollectionRepository $collectionRepository
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $cube = $cubeRepository->find($cubeId);
        if (!$cube) {
            throw $this->createNotFoundException('Cube non trouvé');
        }

        if ($collectionRepository->userHasCube($this->getUser()->getId(), $cubeId)) {
            $this->addFlash('warning', 'Ce cube est déjà dans votre collection.');
            return $this->redirectToRoute('app_rubiks_cube_show', ['id' => $cubeId]);
        }

        $collection = new UserCollection();
        $collection->setUser($this->getUser());
        $collection->setRubiksCube($cube);
        $collection->setPersonalNote($request->request->get('personalNote'));
        $collection->setCondition($request->request->get('condition'));

        $purchasePrice = $request->request->get('purchasePrice');
        if ($purchasePrice) {
            $collection->setPurchasePrice((float) $purchasePrice);
        }

        $em->persist($collection);
        $em->flush();

        $this->addFlash('success', 'Le cube a été ajouté à votre collection !');
        return $this->redirectToRoute('app_collection_index');
    }

    #[Route('/remove/{id}', name: 'app_collection_remove', methods: ['POST'])]
    public function remove(
        UserCollection $collection,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_USER');

        if ($collection->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $em->remove($collection);
        $em->flush();

        $this->addFlash('success', 'Le cube a été retiré de votre collection.');
        return $this->redirectToRoute('app_collection_index');
    }

    #[Route('/user/{username}', name: 'app_collection_user')]
    public function viewUserCollection(
        string $username,
        UserCollectionRepository $collectionRepository,
        EntityManagerInterface $em
    ): Response {
        $user = $em->getRepository(\App\Entity\User::class)->findOneBy(['username' => $username]);

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $collections = $collectionRepository->findByUser($user->getId());

        return $this->render('collection/user.html.twig', [
            'user' => $user,
            'collections' => $collections,
        ]);
    }
}
