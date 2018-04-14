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
     * @Route("/goals/{goal}/stages", name="new_stage", options={"utf8": true})
     * @Method("POST")
     */
    public function post(Goal $goal, Request $request, FormValidator $validator, FormProcessor $formProcessor, ApiResponse $response)
    {
        $user = $this->getUser();
      
        $stage = new Stage();
        $stage->setUserId($user);
        $stage->setgoal($goal);

        $form = $this->createForm(StageType::class, $stage);
        $formProcessor->processForm($form, $request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

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

    /**
     * @Route("/stages", name="get_stages", options={"utf8": true})
     * @Method("GET")
     */
    public function getAll(ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $stages = $this->getDoctrine()->getRepository(Stage::class)->findByUserId($userId);

        return $response->createResponse(['stages' => $stages]);
    }

    /**
     * @Route("/stages/{id}", name="get_stage", options={"utf8": true})
     * @Method("GET")
     */
    public function getOne($id, ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $stage = $this->getDoctrine()->getRepository(Stage::class)->getByIdAndUserId($id, $userId);

        return $response->createResponse($stage);
    }

    /**
     * @Route("/goals/{id}/stages", name="get_goal_stages", options={"utf8": true})
     * @Method("GET")
     */
    public function getByGoal($id, ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $stages = $this->getDoctrine()->getRepository(Stage::class)->findBy([
            'goal' => $id,
            'userId' => $userId,
        ]);

        return $response->createResponse(['stages' => $stages]);
    }

    /**
     * @Route("/stages/{id}", name="update_stage", options={"utf8": true})
     * @Method("PUT")
     */
    public function put($id, Request $request, FormValidator $validator, FormProcessor $formProcessor, ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $stage = $this->getDoctrine()->getRepository(Stage::class)->getByIdAndUserId($id, $userId);

        $form = $this->createForm(StageType::class, $stage);
        $formProcessor->processForm($form, $request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $response->createResponse($stage, 200);
    }

    /**
     * @Route("/stages/{id}", name="delete_stage", options={"utf8": true})
     * @Method("DELETE")
     */
    public function delete($id, ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $stage = $this->getDoctrine()->getRepository(Stage::class)->getByIdAndUserId($id, $userId);

        $em = $this->getDoctrine()->getManager();
        $em->remove($stage);
        $em->flush();

        return $response->createResponse(null, 204);
    }
}
