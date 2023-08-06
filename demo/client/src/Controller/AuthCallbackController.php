<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthCallbackController extends AbstractController
{
    #[Route('/auth/callback', 'auth_code_callback')]
    public function __invoke(Request $request): Response
    {
        $queryParams = $request->query->all();
        if (!empty($queryParams['code'])) {
            return $this->render('pages/auth_code_callback.html.twig', ['queryParams' => $queryParams]);
        }
        dd($request);
    }
}
