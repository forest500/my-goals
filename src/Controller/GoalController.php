<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Goal;
use App\Entity\Category;

class GoalController extends Controller
{
    /**
     * @Route("/new_goal/{category}", name="new_goal", options={"utf8": true})
     * @Method("POST")
     */
    public function new(Category $category, Request $request, ValidatorInterface $validator)
    {
        $name = $request->get('name');

        $goal = new Goal();
        $goal->setName($name);
        $goal->setCategory($category);

        $errors = $validator->validate($goal);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($goal);
        $em->flush();

        return new Response('Dodano cel!');
    }

    /**
     * @Route("/get_goals", name="get_goals", options={"utf8": true})
     * @Method("GET")
     */
    public function getAll(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Goal::class)->findGoals();

        return $this->json($categories);
    }   
    
    /**
     * @Route("/get_goal/{id}", name="get_goal", options={"utf8": true})
     * @Method("GET")
     */
    public function getOne($id, Request $request)
    {
        $goal = $this->getDoctrine()->getRepository(Goal::class)->findGoal($id);
        
        return $this->json($goal);
    }        

    /**
     * @Route("/update_goal/{goal}", name="update_goal", options={"utf8": true})
     * @Method("PUT")
     */
    public function update(Goal $goal, Request $request, ValidatorInterface $validator)
    {
        $name = $request->get('name');
        $categoryId = $request->get('category');
        $category = $this->getDoctrine()->getRepository(Category::class)->find($categoryId);

        $goal->setName($name);
        $goal->setCategory($category);
        $errors = $validator->validate($goal);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new Response("Cel $name został zmodyfikowany");
    }

    /**
     * @Route("/update_goal_status/{goal}", name="update_goal_status", options={"utf8": true})
     * @Method("PUT")
     */
    public function updateStatus(Goal $goal, Request $request, ValidatorInterface $validator)
    {
        $status = $request->get('status');
        $name = $goal->getName();
        
        $goal->setStatus($status);

        $errors = $validator->validate($goal);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new Response("Status celu $name został zmieniony na $status");
    }    

    /**
     * @Route("/delete_goal/{goal}", name="delete_goal", options={"utf8": true})
     * @Method("DELETE")
     */
    public function delete(Goal $goal, Request $request)
    {
        $em = $this->getDoctrine()->getManager();       
        $em->remove($goal);
        $em->flush();

        return new Response("Cel został usunięty");
    }        
}