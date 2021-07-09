<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Authentication manager is used to manage user authentication.
 *
 * @author Pit Solutions Pvt Ltd
 */
class ApiAuthenticationManager
{

    /** @var EntityManagerInterface */
    public $em;

    /**
     * Constructor function.
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Function to get contact login  response.
     */
    public function registerUser(array $data)
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $data['username']]);

        if (null != $user) {
            return new JsonResponse(["error" => $data['username'].' is already registered !'], 500);
        }

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(array(
            'password' => new Assert\NotBlank(),
            'username' => new Assert\Email(),
        ));


        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {
            return new JsonResponse(["error" => (string) $violations], 500);
        }
        $email = $data['username'];



        //Encode the password (you could also do this via Doctrine listener)
        $user = new User();
        $password = $this->passwordEncoder->encodePassword($user, $data['password']);
        $user
//                ->setUsername($username)
                ->setPassword($password)
                ->setEmail($email)
                ->setRoles(['ROLE_USER'])
        ;
        try {
            //save the User!
            $this->em->persist($user);
            $this->em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(["error" => $e->getMessage()], 500);
        }
        return new JsonResponse(["success" => $user->getUsername() . " has been registered!"], 200);
 
    }
}
