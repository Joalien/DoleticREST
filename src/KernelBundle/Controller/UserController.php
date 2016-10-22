<?php

namespace KernelBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use KernelBundle\Entity\User;
use KernelBundle\Form\UserType;

class UserController extends FOSRestController
{

    /**
     * Get all the users
     * @return array
     *
     * @ApiDoc(
     *  section="User",
     *  description="Get all users",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "need validations" = "#ff0000"
     *  }
     * )
     *
     * @View()
     * @Get("/users")
     */
    public function getUsersAction(){

        $users = $this->getDoctrine()->getRepository("KernelBundle:User")
            ->findAll();

        return array('users' => $users);
    }

    /**
     * Get a user by ID
     * @param User $user
     * @return array
     *
     * @ApiDoc(
     *  section="User",
     *  description="Get a user",
     *  requirements={
     *      {
     *          "name"="user",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="user id"
     *      }
     *  },
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "need validations" = "#ff0000"
     *  }
     * )
     *
     * @View()
     * @ParamConverter("user", class="KernelBundle:User")
     * @Get("/user/{id}",)
     */
    public function getUserAction(User $user){

        return array('user' => $user);

    }

    /**
     * Create a new User
     * @var Request $request
     * @return View|array
     *
     * @ApiDoc(
     *  section="User",
     *  description="Create a new User",
     *  input="KernelBundle\Form\UserType",
     *  output="KernelBundle\Entity\User",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "need validations" = "#ff0000"
     *  },
     *  views = { "premium" }
     * )
     *
     * @View()
     * @Post("/user")
     */
    public function postUserAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return array("user" => $user);

        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Edit a User
     * Put action
     * @var Request $request
     * @var User $user
     * @return array
     *
     * @ApiDoc(
     *  section="User",
     *  description="Edit a User",
     *  requirements={
     *      {
     *          "name"="user",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="user id"
     *      }
     *  },
     *  input="KernelBundle\Form\UserType",
     *  output="KernelBundle\Entity\User",
     *  statusCodes={
     *         200="Returned when successful"
     *  },
     *  tags={
     *   "stable" = "#4A7023",
     *   "need validations" = "#ff0000"
     *  },
     *  views = { "premium" }
     * )
     *
     * @View()
     * @ParamConverter("user", class="KernelBundle:User")
     * @Put("/user/{id}")
     */
    public function putUserAction(Request $request, User $user)
    {
        $form = $this->createForm(new UserType(), $user);
        $form->submit($request);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            return array("user" => $user);
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Delete a User
     * Delete action
     * @var User $user
     * @return array
     *
     * @View()
     * @ParamConverter("user", class="KernelBundle:User")
     * @Delete("/user/{id}")
     */
    public function deleteUserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return array("status" => "Deleted");
    }

}