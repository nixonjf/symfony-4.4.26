<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;

class IndexController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    public function redirect_exec()
    { 
        return JsonResponse::create(['data' => ['Welcome '. $this->getUser()->getUsername().' you have now successfully logged in!'], 'status' => Response::HTTP_OK], 200, [], true);
    }

    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    { 
        $user = new User();
        
        $data = json_decode(
            $request->getContent(),
            true
        );
 
        //Encode the password (you could also do this via Doctrine listener)
        $password = $passwordEncoder->encodePassword($user, $data['password']);


        $user->setPassword($password);
        $user->setEmail($data['username']);

        //save the User!
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // ... do any other work - like sending them an email, etc
        // maybe set a "flash" success message for the user

        return JsonResponse::create(['data' => ['success'], 'status' => Response::HTTP_OK], 200, [], true);
    }
}
