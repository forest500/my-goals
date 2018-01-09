<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Stage;
use App\Entity\Goal;

class StageController extends Controller
{
    /**
     * @Route("/new_stage/{goal}", name="new_stage", options={"utf8": true})
     * @Method("POST")
     */
    public function new(Goal $goal, Request $request, ValidatorInterface $validator)
    {
        $name = $request->get('name');
        $award = $request->get('award');
        $endDate = $request->get('endDate');

        $stage = new stage();
        $stage->setName($name);
        $stage->setAward($award);
        $stage->setEndDate(new \DateTIme($endDate));
        $stage->setgoal($goal);
        $number = $stage->getNumber();

        $errors = $validator->validate($stage);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($stage);
        $em->flush();

        return new Response("Dodano poziom $number!");
    }

    /**
     * @Route("/get_stages", name="get_stages", options={"utf8": true})
     * @Method("GET")
     */
    public function getAll(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Stage::class)->findStages();

        return $this->json($categories);
    }   
    
    /**
     * @Route("/get_stage/{id}", name="get_stage", options={"utf8": true})
     * @Method("GET")
     */
    public function getOne($id, Request $request)
    {
        $stage = $this->getDoctrine()->getRepository(Stage::class)->findStage($id);
        
        return $this->json($stage);
    }      
    
    /**
     * @Route("/get_goal_stages/{goal}", name="get_goal_stages", options={"utf8": true})
     * @Method("GET")
     */
    public function getByGoal(Goal $goal, Request $request)
    {
        $stage = $this->getDoctrine()->getRepository(Stage::class)->findByGoal($goal->getId());
        
        return $this->json($stage);
    }     

    /**
     * @Route("/update_stage/{stage}", name="update_stage", options={"utf8": true})
     * @Method("PUT")
     */
    public function update(Stage $stage, Request $request, ValidatorInterface $validator)
    {
        $name = $request->get('name');
        $award = $request->get('award');
        $endDate = $request->get('endDate');
        $goalId = $request->get('goal');
        $goal = $this->getDoctrine()->getRepository(Goal::class)->find($goalId);

        $stage->setName($name);
        $stage->setAward($award);
        $stage->setEndDate(new \DateTIme($endDate));
        $stage->setgoal($goal);

        $errors = $validator->validate($stage);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new Response("Poziom został zmodyfikowany");
    }

    /**
     * @Route("/update_stage_status/{stage}", name="update_stage_status", options={"utf8": true})
     * @Method("PUT")
     */
    public function updateStatus(Stage $stage, Request $request, ValidatorInterface $validator)
    {
        $status = $request->get('status');
        $name = $stage->getName();
        
        $stage->setStatus($status);

        $errors = $validator->validate($stage);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new Response("$name został zmieniony na $status");
    }    

    /**
     * @Route("/delete_stage/{stage}", name="delete_stage", options={"utf8": true})
     * @Method("DELETE")
     */
    public function delete(Stage $stage, Request $request)
    {
        $em = $this->getDoctrine()->getManager();       
        $em->remove($stage);
        $em->flush();

        return new Response("Poziom został usunięty");
    }        
}