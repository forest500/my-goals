<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Api\FormValidator;
use App\Api\FormProcessor;
use App\Api\DeleteProcessor;
use App\Entity\Goal;
use App\Entity\Stage;
use App\Entity\Category;
use App\Form\GoalType;
use App\Api\ApiResponse;

/**
 * @Route("/api")
 */
class GoalController extends Controller
{
    /**
     * @Route("/new_goal/{category}", name="new_goal", options={"utf8": true})
     * @Method("POST")
     */
    public function post(Category $category, Request $request, FormValidator $validator, FormProcessor $formProcessor, ApiResponse $response)
    {
        $goal = new Goal();
        $goal->setCategory($category);
        $user = $this->getUser();

        $form = $this->createForm(GoalType::class, $goal);
        $formProcessor->processForm($form, $request, $user->getId());

        if ($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $goal->setUserId($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($goal);
            $em->flush();

            $response = $response->createResponse($goal, 201);
            $goalUrl = $this->generateUrl(
                'get_goal',
                ['id' => $goal->getId()]
            );
            $response->headers->set('Location', $goalUrl);

            return $response;
        }
    }

    /**
     * @Route("/get_goals", name="get_goals", options={"utf8": true})
     * @Method("GET")
     */
    public function getAll(ApiResponse $response)
    {
        $userId = $this->getUser()->getId();

        $goals = $this->getDoctrine()->getRepository(Goal::class)->findByUserId($userId);

        return $response->createResponse(['goals' => $goals]);
    }

    /**
     * @Route("/get_goal/{id}", name="get_goal", options={"utf8": true})
     * @Method("GET")
     */
    public function getOne($id, ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $goal = $this->getDoctrine()->getRepository(Goal::class)->findOneBy([
            'id' => $id,
            'userId' => $userId,
        ]);
        if (!$goal) {
            throw $this->createNotFoundException(sprintf(
                'Nie znaleziono celu o id "%s"',
                $id
            ));
        }

        return $response->createResponse($goal);
    }

    /**
     * @Route("/get_category_goals/{category}", name="get_category_goals", options={"utf8": true})
     * @Method("GET")
     */
    public function getByCategory(Category $category, ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $goals = $this->getDoctrine()->getRepository(Goal::class)->findBy([
            'category' => $category,
            'userId' => $userId,
        ]);

        return $response->createResponse(['goals' => $goals]);
    }

    /**
     * @Route("/update_goal/{goal}", name="update_goal", options={"utf8": true})
     * @Method("PUT")
     */
    public function put(Goal $goal, Request $request, FormValidator $validator, FormProcessor $formProcessor, ApiResponse $response)
    {
        $form = $this->createForm(GoalType::class, $goal);
        $formProcessor->processForm($form, $request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $response->createResponse($goal, 200);
        }
    }

    /**
     * @Route("/delete_goal/{goal}", name="delete_goal", options={"utf8": true})
     * @Method("DELETE")
     */
    public function delete(Goal $goal, DeleteProcessor $deleteProcessor, ApiResponse $response)
    {
        $em = $this->getDoctrine()->getManager();
        $goalStages = $em->getRepository(Stage::class)->findByGoal($goal->getId());
        if (!empty($goalStages)) {
            $deleteProcessor->throwForeignKeyException();
        }
        $em->remove($goal);
        $em->flush();

        return $response->createResponse(null, 204);
    }
}
