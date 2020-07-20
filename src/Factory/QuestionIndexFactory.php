<?php

namespace App\Factory;

use App\QuestionIndex\QuestionIndex;

class QuestionIndexFactory
{
    public function createQuestionIndex($questionId, $questionType)
    {
        $questionIndex = new QuestionIndex();
        $questionIndex->setId($questionId);
        $questionIndex->setType($questionType);

        return $questionIndex;
    }
}
