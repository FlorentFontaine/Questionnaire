<?php

namespace App\Controller\Front;

use App\Candidate\CandidateManager;
use App\Manager\CategoryManager;
use App\Manager\QuestionManager;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/question")
 */
class QuestionController extends AbstractController
{
    protected $questionManager;
    protected $categoryManager;
    protected $candidateManager;
    protected $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository, CategoryManager $categoryManager, CandidateManager $candidateManager, QuestionManager $questionManager)
    {
        $this->questionManager = $questionManager;
        $this->categoryManager = $categoryManager;
        $this->candidateManager = $candidateManager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/{categoryId}/{questionId}", name="question.show", defaults={"questionId" = "0"},
     * requirements={"questionId" = "\d+"})
     *
     * @param mixed $questionId
     * @param mixed $categoryId
     */
    public function showQuestion(Request $request, int $questionId, int $categoryId): Response
    {
        $nbreOfPages = $this->categoryManager->getQuantityPages($categoryId);

        if ($request->isMethod('post')) {
            $candidateAnswers = $request->request->all();
            $this->candidateManager->generateIndexAnswers($candidateAnswers, $categoryId);
            if (($questionId + 1) < $nbreOfPages) {
                return $this->redirectToRoute('question.show', ['questionId' =>($questionId + 1), 'categoryId' => $categoryId]);
            }
        }

        return $this->render('question/display.html.twig', [
            'question'      => $this->categoryManager->extractQuestionFromCategoryIndex($questionId, $categoryId),
            'questionId'    => $questionId,
            'categoryId'    => $categoryId,
            'numberOfPages' => $nbreOfPages,
            'categories'    => $this->categoryRepository->findall(), ]);
    }
}
