<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BaseController extends AbstractController
{
    #[Route('/', name: 'app_base')]
    #[Route('', name: 'app_base_index')]

    public function index(): Response
    {
        if ($this->getUser() == null)
        {
            return $this->redirectToRoute('app_login');
        }
       return $this->redirectToRoute('app_dashboard');
    }
}
