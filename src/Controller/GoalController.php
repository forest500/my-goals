<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Goal;
use App\Entity\Category;
use App\Form\GoalType;

class GoalController extends Controller
{
    /**
     * @Route("/new_goal/{category}", name="new_goal", options={"utf8": true})
     * @Method("POST")
     */
    public function post(Category $category, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $goal = new Goal();
        $goal->setCategory($category);

        $form = $this->createForm(GoalType::class, $goal);

        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $goal->setUserId($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($goal);
            $em->flush();

            return $this->json("Dodano cel!");
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);

            return $this->json($errors, 400);
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
    public function put(Goal $goal, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(GoalType::class, $goal);

        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->json("Zmieniono cel!", 201);
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);

            return $this->json($errors, 400);
        }
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
            foreach ($errors as $error) {
                $errorArr[] = $error->getMessage();
            }

            return $this->json($errorArr);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->json("Status celu $name został zmieniony na $status");
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
