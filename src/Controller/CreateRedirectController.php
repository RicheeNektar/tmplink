<?php

namespace App\Controller;

use App\Entity\Redirect;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateRedirectController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    #[Route(path: '/{id<[0-9a-fA-F]{32}>}.mp4', name: 'index', methods: ['GET'])]
    public function __invoke(string $id): Response
    {
        $redirect = Redirect::fromMovieId($id);
        $this->entityManager->persist($redirect);
        $this->entityManager->flush();

        return $this->redirect($this->generateUrl('redirect', ['id' => $redirect->id]));
    }
}