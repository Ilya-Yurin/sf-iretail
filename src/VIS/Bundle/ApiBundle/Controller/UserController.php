<?php
/**
 * User: iyurin
 * Date: 15.11.16
 * Time: 0:50
 */

namespace VIS\Bundle\ApiBundle\Controller;


use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use VIS\Bundle\CoreBundle\Entity\User;
use VIS\Bundle\CoreBundle\Form\UserType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;

/**
 * Class UserController
 * @package VIS\Bundle\ApiBundle\Controller
 *
 * @Rest\Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * Method return user list.
     *
     * Available only for users with role **"ROLE_ADMIN"**.
     *
     * @Rest\Get("/list", name="app_api_user_list")
     * @Rest\QueryParam(name="limit", requirements="-?\d+", default="20", description="Item count limit")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview")
     * @Rest\QueryParam(name="filters", nullable=true, strict=false)
     * @Rest\QueryParam(name="sorting", requirements="(user_type|status|full_name)", description="Sort by.")
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getListAction(ParamFetcher $paramFetcher)
    {
        return $this->viewList(
            $this->getDoctrine()->getRepository("APPCoreBundle:User")->getListQB( $paramFetcher->get("filters"), $paramFetcher->get("sorting")),
            $paramFetcher->all(),
            array(
                'list'
            ));
    }

    /**
     * Method return named user list.
     *
     * Available only for users with role **"ROLE_ADMIN"**.
     *
     * @Rest\Get("/name-list", name="app_api_user_name_list")
     * @Rest\QueryParam(name="limit", requirements="-?\d+", default="20", description="Item count limit")
     * @Rest\QueryParam(name="page", requirements="\d+", default="1", description="Page of the overview")
     * @Rest\QueryParam(name="filters", nullable=true, strict=false)
     *
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getNameListAction(ParamFetcher $paramFetcher)
    {
        return $this->viewList(
            $this->getDoctrine()->getRepository("APPCoreBundle:User")->getListQB($paramFetcher->get("filters"), null),
            $paramFetcher->all(),
            array('user.firstName', 'user.lastName')
        );
    }

    /**
     * Method return user data.
     *
     * Available only for users with role **"ROLE_ADMIN"**.
     *
     * @Rest\Get("/{id}", name="app_api_user_get", requirements={
     *     "id": "\d+"
     * })
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function getUserAction($id)
    {
        if($user = $this->getDoctrine()->getRepository('APPCoreBundle:User')->_findOneById($id)){
            return $this->groupView(array(
                'user.email',
                'user.firstName',
                'user.lastName',
                'user.status',
                'user.userType',
                'user.createdAt',
                'user.updatedAt'
            ), array('user' => $user), JsonResponse::HTTP_OK);
        }

        return $this->view(array('error' => 'Not found'))->setStatusCode(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * Create a new User from submit request
     *
     * Available only for users with role **"ROLE_ADMIN"**.
     *
     * @Rest\Post("", name="app_api_user_create")
     *
     * @Security("has_role('ROLE_ADMIN')")
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
            $user->setUsername(stristr($user->getEmailAddress(), '@', true));
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
                'user.createdAt',
                'user.updatedAt'
            ), array('user' => $user), JsonResponse::HTTP_OK);
        }

        return $this->view($form, JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * Update User by submit request
     *
     * Available only for users with role **"ROLE_ADMIN"**.
     *
     * @Rest\Put("/{id}", name="app_api_user_update", requirements={
     *     "id": "\d+"
     * }))
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param Request $request the request
     * @param int $id the user id
     * @return \FOS\RestBundle\View\View
     */
    public function putUserAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('APPCoreBundle:User')->_findOneById($id);
        // Maybe user cannot be found
        if($user){
            // Save old password before form does not update it
            $oldPassword = $user->getPassword();

            $form = $this->createForm(new UserType(), $user, array(
                'validation_groups' => array('update')
            ));

            $form->submit($request->request->all());

            if ($form->isValid())
            {
                // If password was send - encode and save it
                if($inPassword = $form->get('password')->getData()){
                    $encoder = $this->get('security.encoder_factory')->getEncoder($user);
                    $newPasswordHash = $encoder->encodePassword($inPassword, $user->getSalt());
                    $user->setPassword($newPasswordHash);
                } else {
                    // To prevent not null violation while database saving
                    $user->setPassword($oldPassword);
                }
                // Save user data
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                return $this->groupView(array(
                    'user.email',
                    'user.firstName',
                    'user.lastName',
                    'user.status',
                    'user.userType',
                    'user.createdAt',
                    'user.updatedAt'
                ), array('user' => $user), JsonResponse::HTTP_OK);
            }

            return $this->view($form, JsonResponse::HTTP_BAD_REQUEST);
        }

        return $this->view(array('error' => 'Not found'))->setStatusCode(JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * Remove user by id.
     *
     * Available only for users with role **"ROLE_ADMIN"**.
     *
     * @Rest\Delete("/{id}", name="app_api_user_delete", requirements={
     *     "id": "\d+"
     * })
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @param integer $id User id
     * @return \FOS\RestBundle\View\View
     */
    public function deleteUserAction($id)
    {
        $user = $this->getDoctrine()->getRepository('APPCoreBundle:User')->findOneBy(['id' => $id]);

        // If user not found return 404 status
        if(!$user){
            return $this->view(array('error' => 'Not found'))->setStatusCode(JsonResponse::HTTP_NOT_FOUND);
        }
        // If user try to delete yourself - 400 status
        if($user == $this->getUser()){
            return $this->view(array('error' => 'You cannot remove a login user'))->setStatusCode(JsonResponse::HTTP_BAD_REQUEST);
        }

        try{
            // To send user data
            $userName = $user->getFullName();

            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            return $this->view(array('username' => $userName))->setStatusCode(JsonResponse::HTTP_OK);
        } catch(\Exception $e){
            return $this->view(array('error' => $e->getMessage()), JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}