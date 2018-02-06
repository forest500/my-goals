<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Category;

class CategoryController extends Controller
{
    /**
     * @Route("/new_category", name="new_category")
     * @Method("POST")
     */
    public function new(Request $request, ValidatorInterface $validator)
    {
        $name = $request->get('name');
        $description = $request->get('description');

        $category = new Category();
        $category->setName($name);
        $category->setDescription($description);

        $errors = $validator->validate($category);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $errorArr[] = $error->getMessage();
            }

            return $this->json($errorArr);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        return $this->json('Dodano kategorię!');
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
        $name = $request->get('name');
        $description = $request->get('description');

        $category->setName($name);
        $category->setDescription($description);
        $errors = $validator->validate($category);

        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $errorArr[] = $error->getMessage();
            }

            return $this->json($errorArr);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->json("Nazwa kategorii to $name, a jej opis: $description");
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
            return $this->json("Aby usunąc wybraną kategorie nalezy najpierw usunac cele, ktore sie w niej znajduja");
        }

        return $this->json("Kategoria została usunieta");
    }
}
