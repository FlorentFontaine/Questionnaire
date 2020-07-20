<?php

namespace App\Candidate;

use App\Manager\CategoryManager;
use App\QuestionIndex\QuestionIndex;
use App\Repository\CategoryRepository;
use App\Repository\MCAnswerRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CandidateManager
{
    const CANDIDATE = 'candidate';
    protected $session;
    protected $categoryRepository;
    protected $mcAnswerRepository;
    protected $candidate;
    protected $questionIndex;

    public function __construct(QuestionIndex $questionIndex, Candidate $candidate, MCAnswerRepository $mcAnswerRepository, SessionInterface $session, CategoryRepository $categoryRepository)
    {
        $this->session = $session;
        $this->categoryRepository = $categoryRepository;
        $this->mcAnswerRepository = $mcAnswerRepository;
        $this->candidate = $candidate;
        $this->questionIndex = $questionIndex;
    }

    public function createCandidate(Candidate $candidate): void
    {
        $this->session->set(self::CANDIDATE, $candidate);
    }

    public function getCandidate(): Candidate
    {
        return $this->session->get(self::CANDIDATE);
    }

    public function getName(): string
    {
        $candidate = $this->session->get(self::CANDIDATE);

        return $candidate->getLastName();
    }

    public function getDate(): string
    {
        return $this->candidate->getCreatedAt();
    }

    public function generateIndexAnswers(array $mcAnswers, int $categoryId): void
    {
        $sessionQuestion = $this->session->get(CategoryManager::QUESTION_BY_CATEGORY);

        foreach ($mcAnswers as $question => $answers) {
            if (is_array($answers)) {
                foreach ($sessionQuestion[$categoryId] as $questionIndex) {
                    if ($questionIndex->getId() == $question && 'QCM' == $questionIndex->getType()) {
                        $answersIndex[] = $questionIndex->setAnswer($answers);
                    }
                }
            }
            if (!is_array($answers)) {
                foreach ($sessionQuestion[$categoryId] as $questionIndex) {
                    if ($questionIndex->getId() == $question && 'QT' == $questionIndex->getType()) {
                        $questionIndex->setAnswer($answers);
                    }
                }
            }

            $this->session->set(CategoryManager::QUESTION_BY_CATEGORY, $sessionQuestion);
        }
    }
}
