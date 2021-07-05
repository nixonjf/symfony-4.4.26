<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert; 
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Util\Utils;

/**
 * Authentication manager is used to manage user authentication.
 *
 * @author Pit Solutions Pvt Ltd
 */
class ApiAuthenticationManager {

    /** @var EntityManagerInterface */
    public $em;

    /**
     * Constructor function.
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder) {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Function to get contact login  response.
     */
    public function registerUser(array $data) {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $data['username']]);

        if (null != $user) {
            return Utils::generateJsonResponse(500, ["message" => $data['username'] . ' is already registered !']);
        }

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(array(
            'password' => new Assert\NotBlank(),
            'username' => new Assert\Email(),
        ));


        $violations = $validator->validate($data, $constraint);
        if ($violations->count() > 0) {

            return Utils::generateJsonResponse(500, ["message" => (string) $violations]);
        }
        $email = $data['username'];



        //Encode the password (you could also do this via Doctrine listener)
        $user = new User();
        $password = $this->passwordEncoder->encodePassword($user, $data['password']);
        $user
                ->setPassword($password)
                ->setEmail($email)
                ->setRoles(['ROLE_USER'])
        ;
        try {
            //save the User!
            $this->em->persist($user);
            $this->em->flush();
        } catch (\Exception $e) {
            return Utils::generateJsonResponse(500, ["message" => $e->getMessage()]);
        }

        return Utils::generateJsonResponse(200, ["message" => $user->getUsername() . " has been registered!"]);
    }

}
