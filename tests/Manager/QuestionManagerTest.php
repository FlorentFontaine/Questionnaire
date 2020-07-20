<?php

use App\Entity\AbstractQuestion;
use App\Entity\MCQuestion;
use App\Entity\TextQuestion;
use App\Factory\QuestionIndexFactory;
use App\Manager\QuestionManager;
use App\QuestionIndex\QuestionIndex;
use App\Repository\MCQuestionRepository;
use App\Repository\TextQuestionRepository;
use PHPUnit\Framework\TestCase;

class QuestionManagerTest extends TestCase
{
    public $questionManager;
    public $textQuestionRepository;
    public $questionIndexFactory;
    public $mcQuestionRepository;

    protected function setUp(): void
    {
        $this->textQuestionRepository = $this->createMock(TextQuestionRepository::class);
        $this->mcQuestionRepository = $this->createMock(MCQuestionRepository::class);
        $this->questionIndexFactory = $this->createMock(QuestionIndexFactory::class);
        $this->questionManager = new QuestionManager($this->questionIndexFactory ,$this->textQuestionRepository, $this->mcQuestionRepository);
    }

    /**
     * @dataProvider provideGetQuestionType
     * @throws ReflectionException
     */
    public function testGetQuestionType(AbstractQuestion $question ,string $expectedTypeClass): void
    {
        $class = new \ReflectionClass($this->questionManager);
        $getQuestionType = $class->getMethod('getQuestionType');
        $getQuestionType->setAccessible(true);
        $type = $getQuestionType->invokeArgs($this->questionManager, [$question]);
        $this->assertSame($expectedTypeClass, $type);
    }

    /**
     * @dataProvider provideCompareQuestionsEquals
     */
    public function testCompareQuestionsEquals(AbstractQuestion $AbstractQuestion, $questionPositionA, $questionPositionB, $expectedCompareAbstractQuestion): void
    {
        $textQuestionA = $this->createMock(TextQuestion::class);
        $textQuestionB = $this->createMock(TextQuestion::class);

        $textQuestionA
            ->method('getPosition')
            ->willReturn($questionPositionA);

        $textQuestionB
            ->method('getPosition')
            ->willReturn($questionPositionB);

       $compareQuestionPosition = $this->questionManager->compareQuestions($textQuestionA,$textQuestionB);
       $this->assertEquals($expectedCompareAbstractQuestion, $compareQuestionPosition);
    }

    public function testCreateIndex(): void
    {
        $textQuestion = $this->createMock(TextQuestion::class);
        $textQuestion2 = $this->createMock(TextQuestion::class);
        $questionIndex = $this->createMock(QuestionIndex::class);

        $textQuestions = [
            1=> $textQuestion,
            2=> $textQuestion2,
        ];

        $textQuestion
            ->method('getId')
            ->willReturn(2);

        $this->questionIndexFactory
            ->method('createQuestionIndex')
            ->willReturn($questionIndex);

        $questionsIndex = $this->questionManager->createIndex($textQuestions);
        $this->assertArrayHasKey(0, [$questionsIndex]);
        $this->assertInstanceOf(QuestionIndex::class ,$questionsIndex[0]);
    }

    /**
     * @dataProvider  provideGetQuestionByIndex
     */
    public function testGetQuestionByIndex(string $questionIndexType, $expectedInstanceOfQuestion): void
    {
        $questionIndex = $this->createMock(QuestionIndex::class);
        $mcQuestion = $this->createMock(MCQuestion::class);
        $textQuestion = $this->createMock(TextQuestion::class);

        $questionIndex
            ->method('getType')
            ->willReturn($questionIndexType);

        $this->textQuestionRepository
            ->method('find')
            ->willReturn($textQuestion);

        $this->mcQuestionRepository
            ->method('find')
            ->willReturn($mcQuestion);

        $question = $this->questionManager->getQuestionByIndex($questionIndex);
        $this->assertInstanceOf($expectedInstanceOfQuestion, $question);
    }

    public function provideGetQuestionByIndex(): array
    {
        return [
            ["QT", AbstractQuestion::class],
            ["QCM", AbstractQuestion::class]
        ];
    }

    public function provideGetQuestionType(): array
    {
        $mcQuestionMock = $this->createMock(MCQuestion::class);
        $mcQuestionType = QuestionIndex::QCM;

        $textQuestionMock = $this->createMock(TextQuestion::class);
        $textQuestionType = QuestionIndex::QT;

        return [
            [$mcQuestionMock, $mcQuestionType],
            [$textQuestionMock, $textQuestionType],
        ];
    }

    public function provideCompareQuestionsEquals(): array
    {
        $textQuestionEqual = $this->createMock(AbstractQuestion::class);
        $textQuestionLess = $this->createMock(AbstractQuestion::class);
        $textQuestionMore = $this->createMock(AbstractQuestion::class);

        return [
            [$textQuestionEqual, 1, 1, 0],
            [$textQuestionLess, 2, 10 , -1],
            [$textQuestionMore, 8, 2, 1],
        ];
    }
}
