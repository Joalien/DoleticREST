<?php

namespace RHBundle\Controller;


use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use KernelBundle\Entity\Division;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use RHBundle\Entity\UserData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use RHBundle\Entity\Team;
use RHBundle\Form\TeamType;

class TeamController extends FOSRestController
{

    /**
     * Get all the teams
     * @return array
     *
     * @ApiDoc(
     *  section="Team",
     *  description="Get all teams",
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
     * @Get("/teams")
     */
    public function getTeamsAction()
    {

        $teams = $this->getDoctrine()->getRepository("RHBundle:Team")
            ->findAll();

        return array('teams' => $teams);
    }

    /**
     * Get all the teams in a division
     * @param Division $division
     * @return array
     *
     * @ApiDoc(
     *  section="Team",
     *  description="Get all teams in a division",
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
     * @ParamConverter("division", class="KernelBundle:Division")
     * @Get("/teams/division/{id}", requirements={"id" = "\d+"})
     */
    public function getTeamsByDivisionAction(Division $division)
    {

        $teams = $this->getDoctrine()->getRepository("RHBundle:Team")
            ->findBy(['division' => $division]);

        return array('teams' => $teams);
    }

    /**
     * Get all the teams let by a UserData
     * @param UserData $leader
     * @return array
     *
     * @ApiDoc(
     *  section="Team",
     *  description="Get all teams led by a UserData",
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
     * @ParamConverter("leader", class="RHBundle:UserData")
     * @Get("/teams/leader/{id}", requirements={"id" = "\d+"})
     */
    public function getTeamsByLeaderAction(UserData $leader)
    {

        $teams = $this->getDoctrine()->getRepository("RHBundle:Team")
            ->findBy(['leader' => $leader]);

        return array('teams' => $teams);
    }

    /**
     * Get all the teams a UserData belongs to
     * @param UserData $member
     * @return array
     *
     * @ApiDoc(
     *  section="Team",
     *  description="Get all teams a UserData belongs to",
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
     * @ParamConverter("member", class="RHBundle:UserData")
     * @Get("/teams/member/{id}", requirements={"id" = "\d+"})
     */
    public function getTeamsByMemberAction(UserData $member)
    {

        $teams = $this->getDoctrine()->getRepository("RHBundle:Team")
            ->findUserDataTeams($member);

        return array('teams' => $teams);
    }

    /**
     * Get a team by ID
     * @param Team $team
     * @return array
     *
     * @ApiDoc(
     *  section="Team",
     *  description="Get a team",
     *  requirements={
     *      {
     *          "name"="team",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="team id"
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
     * @ParamConverter("team", class="RHBundle:Team")
     * @Get("/team/{id}", requirements={"id" = "\d+"})
     */
    public function getTeamAction(Team $team)
    {

        return array('team' => $team);

    }

    /**
     * Get a team by name
     * @param string $name
     * @return array
     *
     * @ApiDoc(
     *  section="Team",
     *  description="Get a team",
     *  requirements={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="team name"
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
     * @Get("/team/{name}")
     */
    public function getTeamByNameAction($name)
    {

        $team = $this->getDoctrine()->getRepository('RHBundle:Team')->findOneBy(['name' => $name]);
        return array('team' => $team);
    }

    /**
     * Create a new Team
     * @var Request $request
     * @return View|array
     *
     * @ApiDoc(
     *  section="Team",
     *  description="Create a new Team",
     *  input="RHBundle\Form\TeamType",
     *  output="RHBundle\Entity\Team",
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
     * @Post("/team")
     */
    public function postTeamAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_RH_ADMIN');

        $team = new Team();
        $form = $this->createForm(new TeamType(), $team);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($team);
            $em->flush();

            return array("team" => $team);

        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Edit a Team
     * Put action
     * @var Request $request
     * @var Team $team
     * @return array
     *
     * @ApiDoc(
     *  section="Team",
     *  description="Edit a Team",
     *  requirements={
     *      {
     *          "name"="team",
     *          "dataType"="string",
     *          "requirement"="*",
     *          "description"="team id"
     *      }
     *  },
     *  input="RHBundle\Form\TeamType",
     *  output="RHBundle\Entity\Team",
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
     * @ParamConverter("team", class="RHBundle:Team")
     * @Put("/team/{id}")
     */
    public function putTeamAction(Request $request, Team $team)
    {

        if (
            $this->getUser()->getUserData()->getId() !== $team->getLeader()->getId()
            && $this->isGranted('ROLE_RH_SUPERADMIN' === false)
        ) {
            throw new AccessDeniedException();
        }

        $form = $this->createForm(new TeamType(), $team);
        $form->submit($request);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($team);
            $em->flush();

            return array("team" => $team);
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Delete a Team
     * Delete action
     * @var Team $team
     * @return array
     *
     * @View()
     * @ParamConverter("team", class="RHBundle:Team")
     * @Delete("/team/{id}")
     */
    public function deleteTeamAction(Team $team)
    {
        if (
            $this->getUser()->getUserData()->getId() !== $team->getLeader()->getId()
            && $this->isGranted('ROLE_RH_SUPERADMIN' === false)
        ) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();

        return array("status" => "Deleted");
    }

}