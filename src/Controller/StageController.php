<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Api\FormValidator;
use App\Api\FormProcessor;
use App\Entity\Stage;
use App\Entity\Goal;
use App\Entity\Category;
use App\Form\StageType;

/**
 * @Route("/api")
 */
class StageController extends Controller
{
    /**
     * @Route("/new_stage/{goal}", name="new_stage", options={"utf8": true})
     * @Method("POST")
     */
    public function post(Goal $goal, Request $request, FormValidator $validator, FormProcessor $formProcessor)
    {
        $stage = new stage();
        $stage->setgoal($goal);

        $form = $this->createForm(StageType::class, $stage);
        $formProcessor->processForm($form, $request);

        if($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if($form->isSubmitted() && $form->isValid()) {
          $user = $this->getUser();
          $stage->setUserId($user);

          $em = $this->getDoctrine()->getManager();
          $em->persist($stage);
          $em->flush();

          return $this->json("Dodano poziom!", 201);
        }
    }

    /**
     * @Route("/get_stages", name="get_stages", options={"utf8": true})
     * @Method("GET")
     */
    public function getAll(Request $request)
    {
        $userId = $this->getUser()->getId();

        $categories = $this->getDoctrine()->getRepository(Stage::class)->findStages($userId);

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
     * @Route("/get_category_stages/{category}", name="get_category_stages", options={"utf8": true})
     * @Method("GET")
     */
    public function getByCategory(Category $category, Request $request)
    {
        $stage = $this->getDoctrine()->getRepository(Stage::class)->findByCategory($category->getId());

        return $this->json($stage);
    }

    /**
     * @Route("/update_stage/{stage}", name="update_stage", options={"utf8": true})
     * @Method("PUT")
     */
    public function put(Stage $stage, Request $request, FormValidator $validator, FormProcessor $formProcessor)
    {
      $form = $this->createForm(StageType::class, $stage);
      $formProcessor->processForm($form, $request);

      if($form->isSubmitted() && !$form->isValid()) {
          return $validator->createValidationErrorResponse($form);
      }

      if($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->json("Zmodyfikowano poziom!");
      }
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

        return $this->json("Poziom został usunięty");
    }
}
