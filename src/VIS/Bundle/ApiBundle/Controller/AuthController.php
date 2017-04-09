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
use VIS\Bundle\CoreBundle\Form\AccessTokenType;
use VIS\Bundle\CoreBundle\Form\ForgotPasswordType;
use VIS\Bundle\CoreBundle\Form\TokenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * Create access token for user. Main authenticate method.
     *
     * @Rest\Post("/token", name="vis_api_access_token")
     */
    public function createAccessTokenAction(Request $request)
    {
        $form = $this->createForm(new AccessTokenType());

        $form->submit($request->request->all());

        if ($form->isValid())
        {
            $formData = $form->getData();
            /* @var $user User*/
            if ($user = $this->getDoctrine()->getRepository('APP\Bundle\CoreBundle\Entity\User')->_findByEmail($formData['emailAddress']))
            {
                $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                $inPassword = $encoder->encodePassword($formData['password'], $user->getSalt());

                if ($inPassword == $user->getPassword())
                {
                    if (!$user->isEnabled()) {
                        return $this->view(array('error' => 'Your account has been temporarily suspended. Please contact your administrator for details.'))->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
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
     * User logout endpoint.
     *
     * @Rest\Delete("/logout", name="vis_api_logout")
     *
     * @Security("has_role('ROLE_USER')")
     */
    public function logoutAction() {
        $this->get('vis_api.security.token_auth.token_manager')->logout($this->get('security.context')->getToken()->value);

        return $this->view(array('result' => true), JsonResponse::HTTP_NO_CONTENT);
    }
}