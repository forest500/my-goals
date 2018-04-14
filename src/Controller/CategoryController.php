<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Api\FormValidator;
use App\Api\FormProcessor;
use App\Api\DeleteProcessor;
use App\Api\ApiResponse;
use App\Entity\Category;
use App\Entity\Goal;
use App\Form\CategoryType;

/**
 * @Route("/api")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/categories", name="new_category")
     * @Method("POST")
     */
    public function post(Request $request, FormValidator $validator, FormProcessor $formProcessor, ApiResponse $response)
    {
        $user = $this->getUser();
        $category = new Category();
        $category->setUserId($user);

        $form = $this->createForm(CategoryType::class, $category);
        $formProcessor->processForm($form, $request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        $response = $response->createResponse($category, 201);
        $categoryUrl = $this->generateUrl(
              'get_category',
              ['id' => $category->getId()]
        );
        $response->headers->set('Location', $categoryUrl);

        return $response;
    }

    /**
     * @Route("/categories", name="get_categories")
     * @Method("GET")
     */
    public function getAll(ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findByUserId($userId);

        return $response->createResponse(['categories' => $categories]);
    }

    /**
     * @Route("/categories/{id}", name="get_category")
     * @Method("GET")
     */
    public function getOne($id, ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $category = $this->getDoctrine()->getRepository(Category::class)->getByIdAndUserId($id, $userId);

        return $response->createResponse($category);
    }

    /**
     * @Route("/categories/{id}", name="update_category", options={"utf8": true})
     * @Method("PUT")
     */
    public function put($id, Request $request, FormValidator $validator, FormProcessor $formProcessor, ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $category = $this->getDoctrine()->getRepository(Category::class)->getByIdAndUserId($id, $userId);

        $form = $this->createForm(CategoryType::class, $category);
        $formProcessor->processForm($form, $request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $response->createResponse($category, 200);
        }
    }

    /**
     * @Route("/categories/{id}", name="delete_category", options={"utf8": true})
     * @Method("DELETE")
     */
    public function delete($id, DeleteProcessor $deleteProcessor, ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $category = $this->getDoctrine()->getRepository(Category::class)->getByIdAndUserId($id, $userId);
        $em = $this->getDoctrine()->getManager();

        $categoryGoals = $em->getRepository(Goal::class)->findByCategory($id);
        if (!empty($categoryGoals)) {
            $deleteProcessor->throwForeignKeyException();
        }

        $em->remove($category);
        $em->flush();

        return $response->createResponse(null, 204);
    }
}
