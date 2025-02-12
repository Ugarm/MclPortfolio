<?php

namespace App\Controller;

use App\Entity\Content;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class MclController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/', name: 'app_mcl')]
    public function index(): Response
    {
        $content = $this->entityManager->getRepository(Content::class)->findAll();

        return $this->render('mcl/index.html.twig', [
            'controller_name' => 'MclController',
            'content' => $content,
        ]);
    }
}
