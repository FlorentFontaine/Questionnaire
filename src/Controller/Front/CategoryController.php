<?php

namespace App\Controller\Front;

use App\Manager\CategoryManager;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    protected $categoryRepository;
    protected $categoryManager;

    public function __construct(CategoryManager $categoryManager, CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->categoryManager = $categoryManager;
    }

    /**
     * @Route("/", name="category.index")
     */
    public function index(): Response
    {
        return $this->render('homePage.html.twig',
            ['categories' => $this->categoryRepository->findall()]);
    }

    /**
     * @Route("/{categoryId}", name="category.questions")
     *
     * @param mixed $categoryId
     */
    public function getQuestionsByCategory(int $categoryId): Response
    {
        $this->categoryManager->generateIndex($categoryId);

        return $this->redirectToRoute('question.show', ['categoryId' => $categoryId]);
    }
}
