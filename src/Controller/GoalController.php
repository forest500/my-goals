<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Api\FormValidator;
use App\Api\FormProcessor;
use App\Api\DeleteProcessor;
use App\Entity\Goal;
use App\Entity\Stage;
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

        if ($form->isSubmitted() && !$form->isValid()) {
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

        $goals = $this->getDoctrine()->getRepository(Goal::class)->findGoals($userId);

        return $this->json(['goals' => $goals]);
    }

    /**
     * @Route("/get_goal/{id}", name="get_goal", options={"utf8": true})
     * @Method("GET")
     */
    public function getOne($id, Request $request)
    {
        $goal = $this->getDoctrine()->getRepository(Goal::class)->findGoal($id);
        if(!$goal) {
            throw $this->createNotFoundException(sprintf(
                'Nie znaleziono celu o id "%s"',
                $id
            ));
        }

        return $this->json($goal);
    }

    /**
     * @Route("/get_category_goals/{category}", name="get_category_goals", options={"utf8": true})
     * @Method("GET")
     */
    public function getByCategory(Category $category, Request $request)
    {
        $goals = $this->getDoctrine()->getRepository(Goal::class)->findByCategory($category->getId());

        return $this->json(['goals' => $goals]);
    }

    /**
     * @Route("/update_goal/{goal}", name="update_goal", options={"utf8": true})
     * @Method("PUT")
     */
    public function put(Goal $goal, Request $request, FormValidator $validator, FormProcessor $formProcessor)
    {
        $form = $this->createForm(GoalType::class, $goal);
        $formProcessor->processForm($form, $request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->json("Zmieniono cel!");
        }
    }

    /**
     * @Route("/delete_goal/{goal}", name="delete_goal", options={"utf8": true})
     * @Method("DELETE")
     */
    public function delete(Goal $goal, Request $request, DeleteProcessor $deleteProcessor)
    {
        $em = $this->getDoctrine()->getManager();
        $goalStages = $em->getRepository(Stage::class)->findByGoal($goal->getId());
        if (!empty($goalStages)) {
            $deleteProcessor->throwForeignKeyException();
        }
        $em->remove($goal);
        $em->flush();

        return $this->json("Cel został usunięty");
    }
}
