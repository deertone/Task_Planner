<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class PlannerController extends Controller
{
    /**
    * @Route("/")
    */
    public function showAction()
    {
        $userId = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AppBundle:Task')->findBy(['user' => $userId]);
        $categories = $em->getRepository('AppBundle:Category')->findBy(['user' => $userId]);
        if (!empty($categories)) {
            return $this->render('planner/show.html.twig', [
            'tasks' => $tasks,
            'categories' => $categories,
        ]);
        } else {
            return $this->render('planner/start.html.twig');
        }
    }

    /**
    * @Route("tasks/{id}", name="taskby_category")
    * @Method("GET")
    */
    public function tasksByCategoryAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AppBundle:Task')->findBy(['category' => $id]);
        return $this->render('planner/tasksby.html.twig', [
        'tasks' => $tasks,
    ]);
    }
}
