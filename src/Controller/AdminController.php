<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController {

    /**
     * Displays the Admin dashboard.
     * 
     * @Route("/admin", name="admin")
     */
    public function index(): Response {
        return $this->render('admin/index.html.twig');
    }

}
