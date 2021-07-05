<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class IndexController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    private $validator;

    public function __construct(
        ValidatorInterface $validator
    ) {
        $this->validator = $validator;
    }

    public function redirect_exec()
    {
        return JsonResponse::create(['data' => ['Welcome ' . $this->getUser()->getUsername() . ' you have now successfully logged in!'], 'status' => Response::HTTP_OK], 200, [], true);
    }
}
