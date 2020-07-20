<?php

namespace App\Manager;

use App\Entity\MCQuestion;
use App\Entity\TextQuestion;
use App\Repository\CategoryRepository;
use App\Repository\MCAnswerRepository;
use App\Repository\MCQuestionRepository;
use App\Repository\TextQuestionRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PdfManager
{
    protected $categoryRepository;
    protected $textQuestionRepository;
    protected $mcQuestionRepository;
    const QUESTION_BY_CATEGORY = 'questionByCategory';
    protected $session;
    protected $questionManager;
    protected $mCAnswerRepository;
    protected $snappy;

    public function __construct(MCAnswerRepository $mCAnswerRepository, Pdf $snappy,SessionInterface $session, QuestionManager $questionManager, CategoryRepository $categoryRepository, TextQuestionRepository $textQuestionRepository, MCQuestionRepository $mcQuestionRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->textQuestionRepository = $textQuestionRepository;
        $this->mcQuestionRepository = $mcQuestionRepository;
        $this->mCAnswerRepository = $mCAnswerRepository;
        $this->session = $session;
        $this->questionManager = $questionManager;
        $this->snappy = $snappy;
    }

    public function getQuestionsByCategory(): array
    {
        $questionIndex = $this->session->get(self::QUESTION_BY_CATEGORY);
        foreach ($questionIndex as $categoryKey => $questionsCategory) {
            $category = $this->categoryRepository->findCategory($categoryKey);
            foreach ($category as $categoryQuestions) {
                $questionsByCategory = null;
                $questionsByCategory = $this->questionIndex($questionsCategory);
                foreach ($questionsByCategory as $key => $questions) {
                    if ($questions instanceof TextQuestion) {
                        $questionsInCategory[(int) $categoryQuestions->getId()][$categoryQuestions->getLabel()][] = [$questions->getText(), $questions->getAnswer(), $questions->getId()];
                    } else {
                        if ($questions instanceof MCQuestion) {
                            $answers = $this->mCAnswerRepository->findMCAnswerByQuestion($questions->getId());
                            $questionsInCategory[(int) $categoryQuestions->getId()][$categoryQuestions->getLabel()][] = [$questions->getText(), $answers, $questions->getId()];
                        }
                    }
                }
            }
        }

        return $questionsInCategory;
    }

    public function questionIndex(array $questions): ?array
    {
        $questionsByCategory = [];
        if (isset($questions)) {
            foreach ($questions as $question) {
                $questionByIndex = $this->questionManager->getQuestionByIndex($question);
                $questionsByCategory[] = $questionByIndex;
            }

            return $questionsByCategory;
        }
    }

    public function generatePdf($html, $name)
    {
        return new PdfResponse(
            $this->snappy->getOutputFromHtml($html),
            $name
        );
    }
}
