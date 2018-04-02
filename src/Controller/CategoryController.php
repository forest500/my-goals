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
use App\Entity\Category;
use App\Entity\Goal;
use App\Form\CategoryType;
use App\Api\ApiProblem;
use App\Api\ApiProblemException;

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
    public function post(Request $request, FormValidator $validator, FormProcessor $formProcessor)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $formProcessor->processForm($form, $request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $category->setUserId($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->json("Dodano kategorię!", 201);
        }
    }

    /**
     * @Route("/get_categories", name="get_categories")
     * @Method("GET")
     */
    public function getAll(Request $request)
    {
        $userId = $this->getUser()->getId();
        $categories = $this->getDoctrine()->getRepository(Category::class)->findCategories($userId);

        return $this->json(['categories' => $categories]);
    }

    /**
     * @Route("/get_category/{id}", name="get_category")
     * @Method("GET")
     */
    public function getOne($id, Request $request)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findCategory($id);
        if(!$category) {
            throw $this->createNotFoundException(sprintf(
                'Nie znaleziono kategorii o id "%s"',
                $id
            ));
        }

        return $this->json($category);
    }

    /**
     * @Route("/update_category/{category}", name="update_category", options={"utf8": true})
     * @Method("PUT")
     */
    public function put(Category $category, Request $request, FormValidator $validator, FormProcessor $formProcessor)
    {
        $form = $this->createForm(CategoryType::class, $category);
        $formProcessor->processForm($form, $request);

        if ($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->json("Kategoria została zmieniona");
        }
    }

    /**
     * @Route("/delete_category/{id}", name="delete_category", options={"utf8": true})
     * @Method("DELETE")
     */
    public function delete(Category $category, Request $request, DeleteProcessor $deleteProcessor)
    {
        $em = $this->getDoctrine()->getManager();
        $categoryGoals = $em->getRepository(Goal::class)->findByCategory($category->getId());
        if (!empty($categoryGoals)) {
            $deleteProcessor->throwForeignKeyException();
        }

        $em->remove($category);
        $em->flush();

        return $this->json("Kategoria została usunieta");
    }
}
