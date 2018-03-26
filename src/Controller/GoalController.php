<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FormValidator;
use App\Service\FormProcessor;
use App\Entity\Goal;
use App\Entity\Category;
use App\Form\GoalType;

/**
 * @Route("/api")
 */
class GoalController extends Controller
{
    /**
     * @Route("/new_goal/{category}", name="new_goal", options={"utf8": true})
     * @Method("POST")
     */
    public function post(Category $category, Request $request, FormValidator $validator, FormProcessor $formProcessor)
    {
        $goal = new Goal();
        $goal->setCategory($category);

        $form = $this->createForm(GoalType::class, $goal);
        $formProcessor->processForm($form, $request);

        if($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $goal->setUserId($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($goal);
            $em->flush();

            return $this->json("Dodano cel!", 201);
        }
    }

    /**
     * @Route("/get_goals", name="get_goals", options={"utf8": true})
     * @Method("GET")
     */
    public function getAll(Request $request)
    {
        $userId = $this->getUser()->getId();

        $categories = $this->getDoctrine()->getRepository(Goal::class)->findGoals($userId);

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
     * @Route("/get_category_goals/{category}", name="get_category_goals", options={"utf8": true})
     * @Method("GET")
     */
    public function getByCategory(Category $category, Request $request)
    {
        $goals = $this->getDoctrine()->getRepository(Goal::class)->findByCategory($category->getId());

        return $this->json($goals);
    }

    /**
     * @Route("/update_goal/{goal}", name="update_goal", options={"utf8": true})
     * @Method("PUT")
     */
    public function put(Goal $goal, Request $request, FormValidator $validator, FormProcessor $formProcessor)
    {
        $form = $this->createForm(GoalType::class, $goal);
        $form = $this->createForm(GoalType::class, $goal);
        $formProcessor->processForm($form, $request);

        if($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->json("Zmieniono cel!", 201);
        }
    }

    /**
     * @Route("/delete_goal/{goal}", name="delete_goal", options={"utf8": true})
     * @Method("DELETE")
     */
    public function delete(Goal $goal, Request $request)
    {
      try {
          $em = $this->getDoctrine()->getManager();
          $em->remove($goal);
          $em->flush();
      } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $e) {
          return $this->json("Aby usunąc wybrany cel nalezy najpierw usunac poziomy, ktore sie w nim znajduja", 400);
      }

        return $this->json("Cel został usunięty");
    }
}
