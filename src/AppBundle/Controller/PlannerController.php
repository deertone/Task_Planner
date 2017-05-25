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
        $query = $em->createQuery('SELECT t FROM AppBundle:Task t WHERE t.realizationDate < :nowdate AND t.user = :userId AND t.status = :status')
        ->setParameters(['nowdate'=> date('Y-m-d'), 'userId' => $userId, 'status'=>'niewykonane']);
        $tasks = $query->getResult();

        $expiredTasks = count($tasks);

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AppBundle:Task')->findBy(['user' => $userId]);
        $categories = $em->getRepository('AppBundle:Category')->findBy(['user' => $userId]);
        if (!empty($categories)) {
            return $this->render('planner/show.html.twig', [
            'tasks' => $tasks,
            'categories' => $categories,
            'expiredTasks' => $expiredTasks,
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
        $categories = $em->getRepository('AppBundle:Category')->findBy(['id' => $id]);
        $count = count($tasks);
        if($count > 0){
            return $this->render('planner/tasksby.html.twig', ['tasks' => $tasks, 'categories' => $categories]);
        } else {
            return $this->render('planner/notasks.html.twig');
        }
    }

    /**
    * @Route("taskaByStatus/{id}", name="taskby_status")
    * @Method("GET")
    */
    public function tasksByStatusAction($id)
    {
        $userId = $this->getUser()->getId();

        if($id == 1){
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery('SELECT t FROM AppBundle:Task t WHERE t.status = :status AND t.user = :userId ORDER BY t.realizationDate DESC')
            ->setParameters(['status'=>'wykonane', 'userId' => $userId]);
            $tasks = $query->getResult();

            return $this->render('planner/status1.html.twig', ['tasks' => $tasks]);
        } else {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery('SELECT t FROM AppBundle:Task t WHERE t.status = :status AND t.user = :userId ORDER BY t.realizationDate DESC')
            ->setParameters(['status'=>'niewykonane', 'userId' => $userId]);
            $tasks = $query->getResult();

            return $this->render('planner/status2.html.twig', ['tasks' => $tasks]);
        }

    }
}
