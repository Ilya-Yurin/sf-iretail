<?php
/**
 * User: iyurin
 * Date: 15.11.16
 * Time: 0:50
 */

namespace VIS\Bundle\ApiBundle\Controller;


use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use VIS\Bundle\CoreBundle\Entity\User;
use VIS\Bundle\CoreBundle\Form\UserType;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Class UserController
 * @package VIS\Bundle\ApiBundle\Controller
 *
 * @Rest\Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * Create a new User from submit request
     *
     * @Rest\Get("/test", name="vis_api_user_test")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function getUserAction()
    {
        $q = 10;

        return $this->view(["test" => "normal" ], JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * Create a new User from submit request
     *
     * @Rest\Post("", name="vis_api_user_create")
     *
     * @param Request $request the request object
     * @return \FOS\RestBundle\View\View
     */
    public function postUserAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(new UserType(), $user, array(
            'validation_groups' => array('create')
        ));

        $form->submit($request->request->all());

        if ($form->isValid())
        {
            // Generate and set password
            $inPassword = $form->get('password')->getData();
            $encoder = $this->get('security.encoder_factory')->getEncoder($user);
            $newPasswordHash = $encoder->encodePassword($inPassword, $user->getSalt());
            $user->setPassword($newPasswordHash);
            $user->setUsername(stristr($user->getEmail(), '@', true));
            // Save user data
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->groupView(array(
                'user.id',
                'user.email',
                'user.firstName',
                'user.lastName',
                'user.status',
                'user.userType',
            ), array('user' => $user), JsonResponse::HTTP_OK);
        }

        return $this->view($form, JsonResponse::HTTP_BAD_REQUEST);
    }
}