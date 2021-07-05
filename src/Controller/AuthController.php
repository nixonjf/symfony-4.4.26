<?php

namespace App\Controller;

use App\Service\ApiAuthenticationManager as AuthenticationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class AuthController extends AbstractController
{

    /**
     * @Route("/register", name="api_auth_register",  methods={"POST"})
     * @param Request $request
     * @param AuthenticationManager $authenticationManager
     * @return JsonResponse
     */
    public function register(Request $request, AuthenticationManager $authenticationManager)
    {
        $data = json_decode(
            $request->getContent(),
            true
        );

        return $authenticationManager->registerUser(
            $data
        );
        
    }
    
}
