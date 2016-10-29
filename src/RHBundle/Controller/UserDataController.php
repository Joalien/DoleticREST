<?php

namespace RHBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use KernelBundle\Entity\Country;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use RHBundle\Entity\Department;
use RHBundle\Entity\RecruitmentEvent;
use RHBundle\Entity\SchoolYear;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use RHBundle\Entity\UserData;
use RHBundle\Form\UserDataType;

class UserDataController extends FOSRestController
{

    /**
     * Get all the user_datas
     * @return array
     *
     * @ApiDoc(
     *  section="UserData",
     *  description="Get all user_datas",
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
     * @Get("/user_datas")
     */
    public function getUserDatasAction()
    {

        $user_datas = $this->getDoctrine()->getRepository("RHBundle:UserData")
            ->findAll();

        return array('user_datas' => $user_datas);
    }

    /**
     * Get all the user datas from a recruitment event
     * @param RecruitmentEvent $event
     * @return array
     *
     * @ApiDoc(
     *  section="UserData",
     *  description="Get all user datas from a recruitment event",
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
     * @ParamConverter("event", class="RHBundle:RecruitmentEvent")
     * @Get("/user_datas/recruitment/{id}", requirements={"id" = "\d+"})
     */
    public function getUserDatasByRecruitmentAction(RecruitmentEvent $event)
    {
        $user_datas = $this->getDoctrine()->getRepository("RHBundle:UserData")
            ->findBy(['recruitmentEvent' => $event]);

        return array('user_datas' => $user_datas);
    }

    /**
     * Get all the user datas in a department
     * @param Department $department
     * @return array
     *
     * @ApiDoc(
     *  section="UserData",
     *  description="Get all user datas in a department",
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
     * @ParamConverter("department", class="RHBundle:Department")
     * @Get("/user_datas/department/{id}", requirements={"id" = "\d+"})
     */
    public function getUserDatasByDepartmentAction(Department $department)
    {
        $user_datas = $this->getDoctrine()->getRepository("RHBundle:UserData")
            ->findBy(['department' => $department]);

        return array('user_datas' => $user_datas);
    }

    /**
     * Get all the user datas in a school year
     * @param SchoolYear $year
     * @return array
     *
     * @ApiDoc(
     *  section="UserData",
     *  description="Get all user datas in a school year",
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
     * @ParamConverter("year", class="RHBundle:SchoolYear")
     * @Get("/user_datas/year/{id}", requirements={"id" = "\d+"})
     */
    public function getUserDatasBySchoolYearAction(SchoolYear $year)
    {
        $user_datas = $this->getDoctrine()->getRepository("RHBundle:UserData")
            ->findBy(['schoolYear' => $year]);

        return array('user_datas' => $user_datas);
    }

    /**
     * Get all the user datas from a country
     * @param Country $country
     * @return array
     *
     * @ApiDoc(
     *  section="UserData",
     *  description="Get all user datas from a country",
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
     * @ParamConverter("country", class="KernelBundle:Country")
     * @Get("/user_datas/country/{id}", requirements={"id" = "\d+"})
     */
    public function getUserDatasByCountryAction(Country $country)
    {
        $user_datas = $this->getDoctrine()->getRepository("RHBundle:UserData")
            ->findBy(['country' => $country]);

        return array('user_datas' => $user_datas);
    }

    /**
     * Get a user_data by ID
     * @param UserData $user_data
     * @return array
     *
     * @ApiDoc(
     *  section="UserData",
     *  description="Get a user_data",
     *  requirements={
     *      {
     *          "name"="user_data",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="user_data id"
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
     * @ParamConverter("user_data", class="RHBundle:UserData")
     * @Get("/user_data/{id}", requirements={"id" = "\d+"})
     */
    public function getUserDataAction(UserData $user_data)
    {

        return array('user_data' => $user_data);

    }

    /**
     * Create a new UserData
     * @var Request $request
     * @return View|array
     *
     * @ApiDoc(
     *  section="UserData",
     *  description="Create a new UserData",
     *  input="RHBundle\Form\UserDataType",
     *  output="RHBundle\Entity\UserData",
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
     * @Post("/user_data")
     */
    public function postUserDataAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_RH_SUPERADMIN');

        $user_data = new UserData();
        $form = $this->createForm(new UserDataType(), $user_data);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user_data);
            $em->flush();

            return array("user_data" => $user_data);

        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Edit a UserData
     * Put action
     * @var Request $request
     * @var UserData $user_data
     * @return array
     *
     * @ApiDoc(
     *  section="UserData",
     *  description="Edit a UserData",
     *  requirements={
     *      {
     *          "name"="user_data",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="user_data id"
     *      }
     *  },
     *  input="RHBundle\Form\UserDataType",
     *  output="RHBundle\Entity\UserData",
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
     * @ParamConverter("user_data", class="RHBundle:UserData")
     * @Put("/user_data/{id}")
     */
    public function putUserDataAction(Request $request, UserData $user_data)
    {
        if (
            $this->getUser()->getUserData()->getId() !== $user_data->getId()
            && $this->isGranted('ROLE_RH_SUPERADMIN') === false
        ) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new UserDataType(), $user_data);
        $form->submit($request);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($user_data);
            $em->flush();

            return array("user_data" => $user_data);
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Delete a UserData
     * Delete action
     * @var UserData $user_data
     * @return array
     *
     * @View()
     * @ParamConverter("user_data", class="RHBundle:UserData")
     * @Delete("/user_data/{id}")
     */
    public function deleteUserDataAction(UserData $user_data)
    {
        $this->denyAccessUnlessGranted('ROLE_RH_SUPERADMIN');

        $em = $this->getDoctrine()->getManager();
        $em->remove($user_data);
        $em->flush();

        return array("status" => "Deleted");
    }

}