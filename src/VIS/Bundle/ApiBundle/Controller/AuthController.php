<?php
/**
 * User: iyurin
 * Date: 14.11.16
 * Time: 0:34
 */

namespace VIS\Bundle\ApiBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bridge\Doctrine\Tests\Fixtures\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use VIS\Bundle\ApiBundle\Security\Authentication\Toke\UserAccessToken;
use VIS\Bundle\CoreBundle\Form\ForgotPasswordType;
use VIS\Bundle\CoreBundle\Form\TokenType;

/**
 * Class AuthController
 * @package VIS\Bundle\ApiBundle\Controller
 *
 * @Rest\Route("/auth")
 *
 */
class AuthController extends AbstractController
{

    /**
     * @Rest\Get("/test", name="vis_api_access_token")
     */
    public function testAction(Request $request)
    {
        var_dump("Hello world");
    }

    /**
     * @Rest\Post("/token", name="vis_api_access_token")
     */
    public function createAccessTokenAction(Request $request)
    {
        $form = $this->createForm(new TokenType());
        var_dump(132);

        $form->submit($request->request->all());

        if ($form->isValid())
        {
            $formData = $form->getData();

            if ($user = $this->getDoctrine()->getRepository('VIS\Bundle\CoreBundle\Entity\User')->findOneBy(['email' => $formData['email']]))
            {
                $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                $inPassword = $encoder->encodePassword($formData['password'], $user->getSalt());

                if ($inPassword == $user->getPassword())
                {
                    if (!$user->isEnabled()) {
                        return $this->view(array('error' => 'Your account has been temporarily suspended. Please contact with us for details.'))->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
                    }

                    $accessToken = $this->get('vis_api.security.token_auth.token_manager')->setAccessToken($user);

                    // authenticate user in current request
                    $token = new UserAccessToken($user->getRoles());
                    $token->setUser($user);
                    $this->get('security.context')->setToken($token);

                    return $this->groupView(array('session_data'), array('token' => $accessToken, 'user' => $user), JsonResponse::HTTP_OK);
                }
            }

            return $this->view(array('error' => 'Your email and/or password are invalid.'))->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->view(array('error' => $form), JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Post("/forgot-password", name="vis_api_forgot_password")
     */
    public function forgotPasswordAction(Request $request)
    {
        $form = $this->createForm(new ForgotPasswordType());
        $form->submit($request);

        if ($form->isValid())
        {
            $email = $form->get('email')->getData();
            $user = $this->getDoctrine()->getRepository('VISCoreBundle:User')->findOneBy(['email' => $email]);
            $newPassword = $user->generatePassword(6);

            // generate and set new password
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $newPasswordHash = $encoder->encodePassword($newPassword, $user->getSalt());
            $user->setPassword($newPasswordHash);

            // Send email to user with new password
            try {
                $message = \Swift_Message::newInstance()
                    ->setFrom(array($this->container->getParameter('noreply_email') => $this->container->getParameter('noreply_name')))
                    ->setTo($user->getEmail())
                    ->setSubject('Password Reset Request')
                    ->setContentType('text/html')
                    ->setBody($this->renderView(
                        'VISApiBundle:Emails:new-password.html.twig',
                        array('name' => $user->getFullName(), 'password' => $newPassword
                        )));

                $this->get('mailer')->send($message);

            } catch (\Exception $e) {
                return $this->view(array('result' => $e->getMessage()), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->view(array('result' => true), JsonResponse::HTTP_CREATED);
        }

        return $this->view($form, JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @Rest\Delete("/logout", name="vis_api_logout")
     */
    public function logoutAction() {
        $this->get('vis_api.security.token_auth.token_manager')->logout($this->get('security.context')->getToken()->value);

        return $this->view(array('result' => true), JsonResponse::HTTP_NO_CONTENT);
    }

}