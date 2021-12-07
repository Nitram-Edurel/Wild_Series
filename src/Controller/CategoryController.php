<?php

// src/Controller/ProgramController.php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * Show all rows from Category’s entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
        return $this->render('/category/index.html.twig', ['categories' => $categories]);
    }
    /**
     * The controller for the category add form
     * Display the form or deal with it
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            
            return $this->redirectToRoute('category_index');
        }
        return $this->render('category/new.html.twig', ["form" => $form->createView(),]);
    }
    /**
     * Getting a category by name
     *
     * @Route("/{categoryName}", name="show")
     * @return Response
     */
    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->findBy(['name' => $categoryName]);
        
        if (!$category) {
            throw $this->createNotFoundException(
                'No category with name : ' . $categoryName
            );
        }
        $programs = $this->getDoctrine()->getRepository(Program::class)->findBy(['category' => $category], ['id' => 'DESC'], $limit = 3);
        
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program with name : ' . $categoryName
            );
        }
        
        return $this->render('/category/show.html.twig', [
            'categoryName' => $categoryName,
            'programs' => $programs
        ]);
    }
}