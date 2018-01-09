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
     * @Route("/new_category", name="new_category", options={"utf8": true})
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
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();

        return new Response('Dodano kategorię!');
    }

    /**
     * @Route("/get_categories", name="get_categories", options={"utf8": true})
     * @Method("GET")
     */
    public function getAll(Request $request)
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findCategories();

        return $this->json($categories);
    }   
    
    /**
     * @Route("/get_category/{id}", name="get_category", options={"utf8": true})
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
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new Response("Nazwa kategorii to $name, a jej opis: $description");
    }

    /**
     * @Route("/delete_category/{category}", name="delete_category", options={"utf8": true})
     * @Method("DELETE")
     */
    public function delete(Category $category, Request $request)
    {
        $em = $this->getDoctrine()->getManager();       
        $em->remove($category);
        $em->flush();

        return new Response("Kategoria została usunięta");
    }        
}