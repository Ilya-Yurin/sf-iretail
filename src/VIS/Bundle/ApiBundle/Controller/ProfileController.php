<?php
/**
 * User: iyurin
 * Date: 09.04.17
 * Time: 19:52
 */

namespace VIS\Bundle\ApiBundle\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Rest\Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * See user profile
     *
     * Available only for users with role **"ROLE_USER"**.
     *
     * @Rest\Get("", name="vis_api_profile_current_get")
     * @Security("has_role('ROLE_USER')")
     */
    public function getUserInfoAction(Request $request)
    {
        $user = $this->getUser();

        return $this->groupView(array(
            'session_data'
        ), array('user' => $user), JsonResponse::HTTP_OK);
    }
}