<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormInterface;
use App\Entity\Stage;
use App\Entity\Goal;
use App\Entity\Category;
use App\Form\StageType;

class StageController extends Controller
{
    /**
     * @Route("/new_stage/{goal}", name="new_stage", options={"utf8": true})
     * @Method("POST")
     */
    public function post(Goal $goal, Request $request, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);

        $stage = new stage();
        $stage->setgoal($goal);

        $form = $this->createForm(StageType::class, $stage);
        $form->submit($data);

        if($form->isSubmitted() && $form->isValid()) {
          $user = $this->getUser();
          $stage->setUserId($user);

          $em = $this->getDoctrine()->getManager();
          $em->persist($stage);
          $em->flush();

          return $this->json("Dodano poziom!", 201);
        }
        if($form->isSubmitted() && !$form->isValid()) {
          $errors = $this->getErrorsFromForm($form);

          return $this->json($errors, 400);
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
    public function put(Stage $stage, Request $request, ValidatorInterface $validator)
    {
      $data = json_decode($request->getContent(), true);

      $form = $this->createForm(StageType::class, $stage);
      $form->submit($data);

      if($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->json("Zmodyfikowano poziom!");
      }
      if($form->isSubmitted() && !$form->isValid()) {
        $errors = $this->getErrorsFromForm($form);

        return $this->json($errors, 400);
      }
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
            foreach ($errors as $error) {
                $errorArr[] = $error->getMessage();
            }

            return $this->json($errorArr);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->json("$name został zmieniony na $status");
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

    private function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }
}
