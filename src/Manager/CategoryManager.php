<?php

namespace App\Manager;

use App\Entity\AbstractQuestion;
use App\QuestionIndex\QuestionIndex;
use App\Repository\CategoryRepository;
use App\Repository\MCQuestionRepository;
use App\Repository\TextQuestionRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CategoryManager
{
    protected $categoryRepository;
    protected $textQuestionRepository;
    protected $mcQuestionRepository;
    const QUESTION_BY_CATEGORY = 'questionByCategory';
    protected $session;
    protected $questionManager;

    public function __construct(SessionInterface $session, QuestionManager $questionManager, CategoryRepository $categoryRepository, TextQuestionRepository $textQuestionRepository, MCQuestionRepository $mcQuestionRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->textQuestionRepository = $textQuestionRepository;
        $this->mcQuestionRepository = $mcQuestionRepository;
        $this->session = $session;
        $this->questionManager = $questionManager;
    }

    public function hasQuestionInCategory(int $categoryId): bool
    {
        return $this->textQuestionRepository->countByCategory($categoryId) > 0 ||
            $this->mcQuestionRepository->countByCategory($categoryId) > 0;
    }

    public function generateIndex(int $categoryId): void
    {
        if (empty($this->session->get(self::QUESTION_BY_CATEGORY))) {
            $allQuestions = $this->getAllQuestions($categoryId);
            $sessionQuestion = $this->session->get(self::QUESTION_BY_CATEGORY);
            $questionByCategory = $this->questionManager->createIndex($allQuestions);
            $sessionQuestion[$categoryId] = $questionByCategory;
            $this->session->set(self::QUESTION_BY_CATEGORY, $sessionQuestion);
        }
    }

    public function generateIndexAllCategories(): void
    {
        $categories = $this->categoryRepository->findall();
        foreach ($categories as $category) {
            $allQuestions = $this->getAllQuestions($category->getId());
            $questionByCategory = $this->questionManager->createIndex($allQuestions);
            $sessionQuestion[$category->getId()] = $questionByCategory;
            $this->session->set(self::QUESTION_BY_CATEGORY, $sessionQuestion);
        }
    }

    public function getAllQuestions(int $categoryId): array
    {
        $questions = $this->textQuestionRepository->findByIdCategory($categoryId);
        $mcquestions = $this->mcQuestionRepository->findByIdCategory($categoryId);
        $allQuestions = array_merge($questions, $mcquestions);
        usort($allQuestions, [$this->questionManager, 'compareQuestions']);

        return $allQuestions;
    }

    public function extractQuestionFromCategoryIndex(int $indexItem, int $categoryId): ?AbstractQuestion
    {
        $sessionQuestion = $this->session->get(self::QUESTION_BY_CATEGORY);
        if (isset($sessionQuestion[$categoryId][$indexItem])) {
            $questionIndex = $sessionQuestion[$categoryId][$indexItem];
            if ($questionIndex instanceof QuestionIndex) {
                return $this->questionManager->getQuestionByIndex($questionIndex);
            }
        }

        return null;
    }

    public function getQuantityPages(int $categoryId): int
    {
        $categoryOfQuestions = $this->session->get(self::QUESTION_BY_CATEGORY);

        return count($categoryOfQuestions[$categoryId]);
    }
}
