<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class PasswordChange extends BaseService {

    /**
     * @var RequestStack
     */
    private $request;

    /**
     * PasswordReset constructor.
     * 
     * @param RequestStack            $request
     */
    public function __construct(  RequestStack $request) {
         $this->request = $request;
    }

    /**
     * Function to change password.
     *
     * @param string $password        password
     * @param string $confirmPassword re-entered password
     *
     * @return array
     */
    public function changePassword($password, $confirmPassword) {
        if ('' == trim($password) || $password != $confirmPassword) {
            return [
                'status' => 401,
                'data' => ['message' => $this->getTranslator()->trans('password.passwords_do_not_match', [], 'trans')],];
        }

        $results = $this->apiRequest->sendRequest(
                'PUT', 'password/change', [
            'contactLoginId' => $this->getUser()->getContactLoginId(),
            'password' => $password,
                ]
        );

        return json_decode($results, true);
    }

}
