<?php

namespace App\Manager;

use App\Entity\AbstractQuestion;
use App\Entity\TextQuestion;
use App\Factory\QuestionIndexFactory;
use App\QuestionIndex\QuestionIndex;
use App\Repository\MCQuestionRepository;
use App\Repository\TextQuestionRepository;

class QuestionManager
{
    protected $textQuestionRepository;
    protected $mcQuestionRepository;
    protected $questionIndexFactory;

    public function __construct(QuestionIndexFactory $questionIndexFactory, TextQuestionRepository $textQuestionRepository, MCQuestionRepository $mcQuestionRepository)
    {
        $this->textQuestionRepository = $textQuestionRepository;
        $this->mcQuestionRepository = $mcQuestionRepository;
        $this->questionIndexFactory = $questionIndexFactory;
    }

    public function createIndex(array $allQuestions): ?array
    {
        $questionsArray = [];
        if (isset($allQuestions)) {
            foreach ($allQuestions as $key=>$question) {
                if ($question instanceof AbstractQuestion) {
                    $questionIndex = $this->questionIndexFactory->createQuestionIndex($question->getId(), $this->getQuestionType($question));
                    $questionsArray[] = $questionIndex;
                }
            }

            return $questionsArray;
        }
    }

    public function getQuestionByIndex($questionIndex): AbstractQuestion
    {
        if (QuestionIndex::QT == $questionIndex->getType()) {
            $question = $this->textQuestionRepository->find($questionIndex->getId());
        } else {
            $question = $this->mcQuestionRepository->find($questionIndex->getId());
        }

        return $question;
    }

    protected function getQuestionType(AbstractQuestion $question): string
    {
        if ($question instanceof TextQuestion) {
            return QuestionIndex::QT;
        }

        return QuestionIndex::QCM;
    }

    public function compareQuestions(AbstractQuestion $a, AbstractQuestion $b): int
    {
        if ($a->getPosition() == $b->getPosition()) {
            return 0;
        }

        return ($a->getPosition() < $b->getPosition()) ? -1 : 1;
    }
}
