<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\FormValidator;
use App\Service\FormProcessor;
use App\Entity\Category;
use App\Form\CategoryType;

/**
 * @Route("/api")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/new_category", name="new_category")
     * @Method("POST")
     */
    public function post(Request $request, FormValidator $validator, FormProcessor $formProcessor)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);
        $formProcessor->processForm($form, $request);

        if($form->isSubmitted() && !$form->isValid()) {
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

        return $this->json($categories);
    }

    /**
     * @Route("/get_category/{id}", name="get_category")
     * @Method("GET")
     */
    public function getOne($id, Request $request)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findCategory($id);

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

        if($form->isSubmitted() && !$form->isValid()) {
            return $validator->createValidationErrorResponse($form);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->json("Kategoria została zmieniona");
        }
    }

    /**
     * @Route("/delete_category/{category}", name="delete_category", options={"utf8": true})
     * @Method("DELETE")
     */
    public function delete(Category $category, Request $request)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        } catch (\Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException $e) {
            return $this->json("Aby usunąc wybraną kategorie nalezy najpierw usunac cele, ktore sie w niej znajduja", 400);
        }

        return $this->json("Kategoria została usunieta");
    }
}
