<?php

namespace App\Controller\Api;

use App\Service\ApiAuthenticationManager as AuthenticationManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class IndexController extends AbstractController {

    /**
     * Sample response for authenticated user.
     *
     *
     * @Route("/index", name="api_index",  methods={"GET"}) 
     * @return JsonResponse
     */
    public function index() {
        return new JsonResponse(["success" => "Welcome " . $this->getUser()->getUsername() . " "], 200);
    }

}
