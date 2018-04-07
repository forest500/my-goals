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
     * @Route("/new_category.{_format}", name="new_category",
    *  defaults={"_format": "json"})
     * @Method("POST")
     */
    public function post(Request $request, FormValidator $validator, FormProcessor $formProcessor, ApiResponse $response)
    {
        $category = new Category();
        $user = $this->getUser();

        $form = $this->createForm(CategoryType::class, $category);
        $formProcessor->processForm($form, $request, $user->getId());

        if ($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setUserId($user);

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
    }

    /**
     * @Route("/get_categories", name="get_categories")
     * @Method("GET")
     */
    public function getAll(ApiResponse $response)
    {
        $userId = $this->getUser()->getId();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findByUserId($userId);

        return $response->createResponse(['categories' => $categories]);
    }

    /**
     * @Route("/get_category/{id}", name="get_category")
     * @Method("GET")
     */
    public function getOne($id, ApiResponse $response)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);
        if (!$category) {
            throw $this->createNotFoundException(sprintf(
                'Nie znaleziono kategorii o id "%s"',
                $id
            ));
        }

        return $response->createResponse($category);
    }

    /**
     * @Route("/update_category/{id}", name="update_category", options={"utf8": true})
     * @Method("PUT")
     */
    public function put(Category $category, Request $request, FormValidator $validator, FormProcessor $formProcessor, ApiResponse $response)
    {
        $userId = $this->getUser()->getId();

        $form = $this->createForm(CategoryType::class, $category);
        $formProcessor->processForm($form, $request, $userId);

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
     * @Route("/delete_category/{id}", name="delete_category", options={"utf8": true})
     * @Method("DELETE")
     */
    public function delete(Category $category, DeleteProcessor $deleteProcessor, ApiResponse $response)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryGoals = $em->getRepository(Goal::class)->findByCategory($category->getId());
        if (!empty($categoryGoals)) {
            $deleteProcessor->throwForeignKeyException();
        }

        $em->remove($category);
        $em->flush();

        return $response->createResponse(null, 204);
    }
}
