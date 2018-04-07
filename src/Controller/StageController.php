<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Api\FormValidator;
use App\Api\FormProcessor;
use App\Entity\Stage;
use App\Entity\Goal;
use App\Entity\Category;
use App\Form\StageType;
use App\Api\ApiResponse;

/**
 * @Route("/api")
 */
class StageController extends Controller
{
    /**
     * @Route("/new_stage/{goal}", name="new_stage", options={"utf8": true})
     * @Method("POST")
     */
    public function post(Goal $goal, Request $request, FormValidator $validator, FormProcessor $formProcessor, ApiResponse $response)
    {
        $stage = new stage();
        $stage->setgoal($goal);
        $user = $this->getUser();

        $form = $this->createForm(StageType::class, $stage);
        $formProcessor->processForm($form, $request, $user->getId());

        if($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if($form->isSubmitted() && $form->isValid()) {
          $stage->setUserId($user);

          $em = $this->getDoctrine()->getManager();
          $em->persist($stage);
          $em->flush();

          $response = $response->createResponse($stage, 201);
          $stagelUrl = $this->generateUrl(
              'get_stage',
              ['id' => $stage->getId()]
          );
          $response->headers->set('Location', $stagelUrl);

          return $response;
        }
    }

    /**
     * @Route("/get_stages", name="get_stages", options={"utf8": true})
     * @Method("GET")
     */
    public function getAll(ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $stages = $this->getDoctrine()->getRepository(Stage::class)->findByUserId($userId);

        return $response->createResponse(['stages' => $stages]);
    }

    /**
     * @Route("/get_stage/{id}", name="get_stage", options={"utf8": true})
     * @Method("GET")
     */
    public function getOne($id, ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $stage = $this->getDoctrine()->getRepository(Stage::class)->findOneBy([
            'id' => $id,
            'userId' => $userId,
        ]);
        if(!$stage) {
            throw $this->createNotFoundException(sprintf(
                'Nie znaleziono poziomu o id "%s"',
                $id
            ));
        }

        return $response->createResponse($stage);
    }

    /**
     * @Route("/get_goal_stages/{goal}", name="get_goal_stages", options={"utf8": true})
     * @Method("GET")
     */
    public function getByGoal(Goal $goal, ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $stages = $this->getDoctrine()->getRepository(Stage::class)->findBy([
            'goal' => $goal,
            'userId' => $userId,
        ]);

        return $response->createResponse(['stages' => $stages]);
    }

    /**
     * @Route("/update_stage/{stage}", name="update_stage", options={"utf8": true})
     * @Method("PUT")
     */
    public function put(Stage $stage, Request $request, FormValidator $validator, FormProcessor $formProcessor, ApiResponse $response)
    {
      $userId = $this->getUser()->getId();
      $form = $this->createForm(StageType::class, $stage);
      $formProcessor->processForm($form, $request, $userId);

      if($form->isSubmitted() && !$form->isValid()) {
          return $validator->createValidationErrorResponse($form);
      }

      if($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $response->createResponse($stage, 200);
      }
    }

    /**
     * @Route("/delete_stage/{stage}", name="delete_stage", options={"utf8": true})
     * @Method("DELETE")
     */
    public function delete(Stage $stage, ApiResponse $response)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($stage);
        $em->flush();

        return $response->createResponse(null, 204);
    }
}
