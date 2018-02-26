<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\FormInterface;
use App\Entity\Category;
use App\Form\CategoryType;

class CategoryController extends Controller
{
    /**
     * @Route("/new_category", name="new_category")
     * @Method("POST")
     */
    public function new(Request $request, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);

        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->json("Dodano kategorię!");
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);

            return $this->json($errors, 400);
        }
    }

    /**
     * @Route("/get_categories", name="get_categories")
     * @Method("GET")
     */
    public function getAll(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findCategories();

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
    public function update(Category $category, Request $request, ValidatorInterface $validator)
    {
        $data = json_decode($request->getContent(), true);

        $form = $this->createForm(CategoryType::class, $category);
        $form->submit($data);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->json("Nazwa kategorii została zmieniona");
        }

        if($form->isSubmitted() && !$form->isValid()) {
            $errors = $this->getErrorsFromForm($form);

            return $this->json($errors, 400);
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
        } catch (\Doctrine\DBAL\DBALException $e) {
            return $this->json("Aby usunąc wybraną kategorie nalezy najpierw usunac cele, ktore sie w niej znajduja", 400);
        }

        return $this->json("Kategoria została usunieta");
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
