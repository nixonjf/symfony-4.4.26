<?php

namespace App\Controller;

use App\Service\PasswordReset;
use App\Service\PasswordChange;
use App\Security\User\WebserviceUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use App\Form\SecurityType;

//use Symfony\Contracts\Translation\TranslatorInterface;

class PasswordController extends AbstractController {

    /**
     * @var PasswordReset
     */
    private $passwordResetService;

    /**
     * @var PasswordChange
     */
    private $passwordChangeService;

    /**
     * PasswordController constructor.
     *
     * @param PasswordReset  $passwordResetService
     * @param PasswordChange $passwordChangeService
     */
    public function __construct(PasswordReset $passwordResetService, PasswordChange $passwordChangeService) {
        $this->passwordResetService = $passwordResetService;
        $this->passwordChangeService = $passwordChangeService;
    }

    /**
     * Displays the change password password form.
     *
     * @param string $parameter
     *
     * @Route("/new-password/{parameter}", name="new-password")
     *
     * @return Response
     */
    public function newPassword(string $parameter) {
        if ($this->getUser()) {
            return $this->redirectToRoute('homepage');
        }
        $username = $this->passwordResetService->checkIfValidToken();
        if (!$username) {
            $this->addFlash('warning', $this->getTranslator()->trans('password.link_expired', [], 'trans'));

            return $this->redirectToRoute('login');
        }

        return $this->render('password/reset.html.twig', [
                    'token' => $parameter,
                    'username' => $username,
                    'updatePasswordForm' => $this->get('form.factory')->createNamed(
                            '', SecurityType::class, null, ['mode' => 'resetPassword'])->createView()
        ]);
    }

    /**
     * Send the reset link to user email.
     *
     * @param Request $request
     *
     * @Route("/send-reset-link", name="send-reset-link")
     *
     * @return Response
     */
    public function sendResetLink(Request $request) {
        $response = $this->passwordResetService->sendResetLink();

        if (201 != $response['status']) {
            $this->addFlash('resetError', $response['data']['message']);

            return $this->redirectToRoute('login', ['_username' => $request->get('_username')]);
        }
        $this->addFlash('success', $this->getTranslator()->trans('password.reset_mail_sent', [], 'trans'));

        return $this->redirectToRoute('login');
    }

    /**
     * Function to handle the new password form submission.
     *
     * @param Request $request
     *
     * @Route("/reset-password", name="reset-password")
     *
     * @return Response
     */
    public function resetPassword(Request $request) {
        $response = $this->passwordResetService->changePassword();
        if (201 != $response['status']) {
            $this->addFlash('warning', $response['data']['message']);

            return $this->redirectToRoute('new-password', ['parameter' => $request->get('_token')]);
        }
        $this->addFlash('success', $this->getTranslator()->trans('password.reset_success', [], 'trans'));

        return $this->login($request, $response['data']);
    }

    /**
     * Displays the change password password form for logged in user.
     *
     * @Route("/load-change-password-form", name="load_change_password_form")
     *
     * @return Response
     */
    public function loadChangePasswordForm(): Response {
        return $this->render('password/change.html.twig', ['changePasswordForm' => $this->get('form.factory')->createNamed('', SecurityType::class, null, ['mode' => 'updatePassword'])
                            ->createView()]
        );
    }

    /**
     * Submit action of form change password.
     *
     * @param Request $request
     *
     * @Route("/change-password", name="change_password")
     *
     * @return Response
     */
    public function changePassword(Request $request): JsonResponse {
        $password = $request->get('_password');
        $confirmPassword = $request->get('_confirm_password');
        $response = $this->passwordChangeService->changePassword($password, $confirmPassword);
        $request->getSession()->getFlashBag()->add('success', $this->getTranslator()->trans('password.reset_success', [], 'trans'));

        return new JsonResponse($response);
    }

    /**
     * Function to login the user after successfully resetting the password.
     *
     * @param Request $request
     * @param array   $response
     *
     * @return Response
     */
    private function login(Request $request, $response) {
        $this->container->get('security.token_storage')->setToken(null);
        $this->container->get('session')->invalidate();
        $user = new WebserviceUser(['username' => $response['loginname'],
            'password' => '',
            'roles' => ['ROLE_ADMIN'],
            'contactLoginId' => $response['contactLoginId'],
            'contactMail' => $response['contactMail'],
            'language' => $response['language'],
            'contactLetterText' => $response['contactLetterText'],
            'contactOwnerId' => $response['contactOwnerId'],
            'groupContactId' => $response['groupContactId'],
            'contactId' => $response['contactId'],
            'contactAvatar' => $response['contactAvatar'],
            'contactName' => $response['preName'] . ' ' . $response['name'],]
        );
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->container->get('security.token_storage')->setToken($token);

        return $this->redirectToRoute('homepage');
    }

    /**
     * Displays the change password password form for logged in user.
     *
     * @Route("/forgot-password", name="forgot_password")
     *
     * @return Response
     */
    public function forgotPassword(Request $request): Response {

        if ($request->isMethod('post')) {

            $response = $this->passwordResetService->sendResetLink();

            if (201 != $response['status']) {
                $this->addFlash('resetError', $response['data']['message']);

                return $this->redirectToRoute('login', ['_username' => $request->get('_username')]);
            }
            $this->addFlash('success', $this->getTranslator()->trans('password.reset_mail_sent', [], 'trans'));

            return $this->redirectToRoute('login');
        }
        return $this->render('password/forgotten.html.twig', ['username' => 1, 'forgotPasswordForm' => $this->get('form.factory')->createNamed('', SecurityType::class, null, ['mode' => 'forgotPassword'])
                            ->createView()]
        );
    }

}
