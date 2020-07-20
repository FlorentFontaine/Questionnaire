<?php

use App\Factory\QuestionIndexFactory;
use App\QuestionIndex\QuestionIndex;
use PHPUnit\Framework\TestCase;

class QuestionIndexFactoryTest extends TestCase
{
    public $questionIndex;
    public $questionIndexFactory;

    protected function setUp(): void
    {
        $this->questionIndexFactory = new QuestionIndexFactory();
    }

    /**
     * @dataProvider provideCreateQuestionIndex
     */
    public function testCreateQuestionIndex(int $questionId, string $questionType,int $expectedQuestionId,string  $expectedQuestionType ): void
    {
        $questionIndex = $this->questionIndexFactory->createQuestionIndex($questionId, $questionType);
        $this->assertInstanceOf(QuestionIndex::class, $questionIndex);
        $this->assertSame($expectedQuestionId, $questionIndex->getId() );
        $this->assertSame($expectedQuestionType, $questionIndex->getType());
    }

    public function provideCreateQuestionIndex(): array
    {
        return
        [
            [2, 'QT', 2, 'QT'],
            [0, 'QCM', 0, 'QCM'],
        ];
    }

}
