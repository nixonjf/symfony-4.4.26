<?php

namespace App\Service;
use Psr\Log\LoggerInterface;

use Symfony\Component\HttpFoundation\RequestStack;

class PasswordReset extends BaseService {

    private $request;
    private $mailer;

    /**
     * Constructor function.
     */
    public function __construct(RequestStack $request, \Swift_Mailer $mailer, LoggerInterface $logger) {
        $this->mailer = $mailer;
//        $this->apiRequest = $apiRequest;
//        $this->templating = $templating;
        $this->logger = $logger;
        $this->request = $request;
    }

    /**
     * Function to send reset password request and get response mail.
     *
     * @return array
     */
    public function sendResetLink() {
        $request = $this->request->getCurrentRequest();


        $message = (new \Swift_Message())
                ->setSubject('$subject')
                ->setFrom('nixin@dd.cc')
                ->setTo('ndixin@dd.cc')
                ->setBody('$body', 'text/html');
        $this->mailer->send($message);



        echo 'link generated';
        // $this->sendEmail();



        die;
        die;



        return true;

        $username = $request->get('_username');
        $results = $this->apiRequest->sendRequest(
                'POST', 'password/send-reset-link', [
            'loginname' => $username,
            'clientURL' => $request->getSchemeAndHttpHost() . '/new-password',
                ]
        );

        return json_decode($results, true);
    }

    /**
     * Function to change password.
     *
     * @param string _password          password
     * @param string _confirm_passworde re-entered password
     *
     * @return array
     */
    public function changePassword() {
        $request = $this->request->getCurrentRequest();
        $password = $request->get('_password');
        $confirmPassword = $request->get('_confirm_password');
        $token = $request->get('_reset_token');
        if ('' != trim($password) && $password != $confirmPassword) {
            return [
                'status' => 401,
                'data' => ['message' => $this->getTranslator()->trans('password.passwords_do_not_match', [], 'trans')],];
        }
        $results = $this->apiRequest->sendRequest(
                'PUT', 'password/reset', [
            'token' => $token,
            'password' => $password,
                ]
        );

        return json_decode($results, true);
    }

    /**
     * Function to check if the reset token is valid.
     *
     * @param string parameter the reset token
     *
     * @return mixed
     */
    public function checkIfValidToken() {
        $request = $this->request->getCurrentRequest();
        $results = $this->apiRequest->sendRequest(
                'POST', 'password/verify-token', [
            'token' => $request->get('parameter'),
                ]
        );

        $results = json_decode($results, true);

        if (201 == $results['status']) {
            return $results['data']['username'];
        }

        return false;
    }

    /**
     * Function to send a  mail.
     *
     * @param string $body
     * @param array  $from
     * @param string $toEmail
     * @param string $subject
     *
     * @retrun boolean
     */
    private function sendEmail(string $body, array $from, string $toEmail, string $subject): bool {
        die;
        $toEmail = 'nixon.fz@pitsolutions.com';

        $isSend = true;

        try {
            $message = (new \Swift_Message())
                    ->setSubject($subject)
                    ->setFrom($from)
                    ->setTo($toEmail)
                    ->setBody($body, 'text/html');
            $this->mailer->send($message);
        } catch (\Exception $e) {
            $this->logger->error($e);
            $isSend = false;
        }

        return $isSend;
    }

}
