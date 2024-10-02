<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main', methods: [Request::METHOD_GET])]
    public function index(PersonRepository $personRepository): Response
    {
        return $this->render('main/index.html.twig', [
            'persons' => $personRepository->findBy(['isBookmarked' => true]),
        ]);
    }
}
