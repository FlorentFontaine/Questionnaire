<?php

use App\Entity\AbstractQuestion;
use App\Entity\MCQuestion;
use App\Entity\TextQuestion;
use App\Manager\CategoryManager;
use App\Manager\QuestionManager;
use App\QuestionIndex\QuestionIndex;
use App\Repository\CategoryRepository;
use App\Repository\MCQuestionRepository;
use App\Repository\TextQuestionRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;

class CategoryManagerTest extends TestCase
{
    public $session = [];
    public $categoryManager;
    public $questionManager;
    public $textQuestionRepository;
    public $mcQuestionRepository;
    public $categoryRepository;

    protected function setUp(): void
    {
        $this->session = $this->createMock(Session::class);
        $this->textQuestionRepository = $this->createMock(TextQuestionRepository::class);
        $this->mcQuestionRepository = $this->createMock(MCQuestionRepository::class);
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        $this->questionManager = $this->createMock(QuestionManager::class);
        $this->categoryManager = new CategoryManager($this->session, $this->questionManager, $this->categoryRepository, $this->textQuestionRepository, $this->mcQuestionRepository);
    }

    public function testGenerateIndex(): void
    {
        $questionIndex86 = $this->createMock(QuestionIndex::class);
        $questionIndex83 = $this->createMock(QuestionIndex::class);
        $questionIndex21 = $this->createMock(QuestionIndex::class);
        $questionIndex22 = $this->createMock(QuestionIndex::class);
        $textQuestion = $this->createMock(TextQuestion::class);
        $mCQuestion = $this->createMock(MCQuestion::class);

        $sessionQuestion = [
            2 => [
                1 => $questionIndex21,
                2 => $questionIndex22,
            ],
            8 =>
                [
                    3 => $questionIndex83,
                    6 => $questionIndex86,
                ]
        ];

        $this->textQuestionRepository
            ->expects($this->once())
            ->method('findByIdCategory')
            ->willReturn([$textQuestion]);

        $this->mcQuestionRepository
            ->expects($this->once())
            ->method('findByIdCategory')
            ->willReturn([$mCQuestion]);

        $this->questionManager
            ->expects($this->once())
            ->method('createIndex')
            ->willReturn($sessionQuestion);

        $this->categoryManager->generateIndex(1);
    }

    public function testGenerateIndexAllCategories(): void
    {
        $questionIndex86 = $this->createMock(QuestionIndex::class);
        $questionIndex83 = $this->createMock(QuestionIndex::class);
        $questionIndex21 = $this->createMock(QuestionIndex::class);
        $questionIndex22 = $this->createMock(QuestionIndex::class);
        $textQuestion = $this->createMock(TextQuestion::class);
        $mCQuestion = $this->createMock(MCQuestion::class);
        $categoryManager = $this->createMock(CategoryManager::class);

        $sessionQuestion = [
            2 => [
                1 => $questionIndex21,
                2 => $questionIndex22,
            ],
            8 =>
                [
                    3 => $questionIndex83,
                    6 => $questionIndex86,
                ]
        ];

        $this->categoryRepository
            ->expects($this->once())
            ->method('findall')
            ->willReturn([$textQuestion]);

        $categoryManager
            ->method('getAllQuestions')
            ->willReturn(1);

        $this->questionManager
            ->expects($this->once())
            ->method('createIndex')
            ->willReturn($sessionQuestion);

        $this->categoryManager->generateIndexAllCategories();
    }

    public function testGetAllQuestions(): void
    {
        $questionIndex86 = $this->createMock(QuestionIndex::class);
        $questionIndex83 = $this->createMock(QuestionIndex::class);
        $questionIndex21 = $this->createMock(QuestionIndex::class);
        $questionIndex22 = $this->createMock(QuestionIndex::class);
        $textQuestion = $this->createMock(TextQuestion::class);
        $mCQuestion = $this->createMock(MCQuestion::class);

        $sessionQuestion = [
            2 => [
                1 => $questionIndex21,
                2 => $questionIndex22,
            ],
            8 =>
                [
                    3 => $questionIndex83,
                    6 => $questionIndex86,
                ]
        ];

        $this->textQuestionRepository
            ->expects($this->once())
            ->method('findByIdCategory')
            ->willReturn([$textQuestion]);

        $this->mcQuestionRepository
            ->expects($this->once())
            ->method('findByIdCategory')
            ->willReturn([$mCQuestion]);

        $allQuestions = $this->categoryManager->getAllQuestions(1);
        $this->assertInstanceOf(AbstractQuestion::class , $allQuestions[0]);
    }

    /**
     * @dataProvider provideHasQuestionInCategory
     */
    public function testHasQuestionInCategory(int $totalTextQuestions, int $totalMcQuestions, int $categoryId, string $expectedHasQuestionInCategory): void
    {
        $this->textQuestionRepository
            ->method('countByCategory')
            ->willReturn($totalTextQuestions);

        $this->mcQuestionRepository
            ->method('countByCategory')
            ->willReturn($totalMcQuestions);

        $QuestionsInCategory = $this->categoryManager->hasQuestionInCategory($categoryId);
        $this->assertEquals($expectedHasQuestionInCategory, $QuestionsInCategory);
    }

    /**
     * @dataProvider provideExtractQuestionFromCategoryIndex
     */
    public function testExtractQuestionFromCategoryIndex(array $sessionQuestion,int $categoryId, int $itemId): void
    {
        $mCQuestion = $this->createMock(MCQuestion::class);

        $this->session
            ->expects($this->once())
            ->method('get')
            ->willReturn($sessionQuestion);

        $this->questionManager
            ->expects($this->once())
            ->method('getQuestionByIndex')
            ->with($sessionQuestion[$categoryId][$itemId])
            ->willReturn($mCQuestion);

        $sessionQuestion = $this->categoryManager->extractQuestionFromCategoryIndex($itemId, $categoryId);
        $this->assertInstanceOf(AbstractQuestion::class, $sessionQuestion);
    }

    /**
     * @dataProvider provideQuestionFromCategoryIndexNull
     */
    public function testQuestionFromCategoryIndexNull(array $sessionQuestion, int $categoryId, int $itemId): void
    {
        $this->session
            ->expects($this->once())
            ->method('get')
            ->willReturn($sessionQuestion);

        $sessionQuestion = $this->categoryManager->extractQuestionFromCategoryIndex($itemId, $categoryId);
        $this->assertNull($sessionQuestion);
    }

    /**
     * @dataProvider provideGetQuantityPages
     */
    public function testGetQuantityPages(array $sessionQuestion, int $categoryId, int $exeptedTotalPages): void
    {
        $this->session
            ->expects($this->once())
            ->method('get')
            ->willReturn($sessionQuestion);

        $totalPages = $this->categoryManager->getQuantityPages($categoryId);
        $this->assertEquals($exeptedTotalPages, $totalPages);
    }


    public function provideGetQuantityPages(): array
    {

        $mCQuestion = $this->createMock(MCQuestion::class);
        $textQuestion = $this->createMock(TextQuestion::class);

        $sessionQuestion = [
            14 => [
                1 => $mCQuestion,
                2 => $textQuestion,
            ],
        ];

        $sessionQuestion1 = [
            11 => [
                1 => $mCQuestion,
            ],
        ];

        return [
            [$sessionQuestion, 14, 2],
            [$sessionQuestion1, 11, 1],
        ];
    }

    public function provideHasQuestionInCategory(): array
    {
        return [
            [2, 5, 2, true],
            [0, 1, 1, true],
            [5, 0, 4, true],
            [0, 0, 3, false],
        ];
    }

    public function provideExtractQuestionFromCategoryIndex(): array
    {
        $questionIndex86 = $this->createMock(QuestionIndex::class);
        $questionIndex83 = $this->createMock(QuestionIndex::class);
        $questionIndex88 = $this->createMock(QuestionIndex::class);
        $questionIndex21 = $this->createMock(QuestionIndex::class);
        $questionIndex22 = $this->createMock(QuestionIndex::class);

        $sessionQuestion = [
            2 => [
                1 => $questionIndex21,
                2 => $questionIndex22,
            ],
            8 =>
                [
                    3 => $questionIndex83,
                    6 => $questionIndex86,
                    8 => $questionIndex88,
                ]
        ];

        return [
            [$sessionQuestion, 2, 1],
        ];
    }

    public function provideQuestionFromCategoryIndexNull(): array
    {
        $questionIndex21 = $this->createMock(QuestionIndex::class);
        $questionIndex22 = $this->createMock(QuestionIndex::class);

        $sessionQuestion = [
            2 => [
                1 => $questionIndex21,
                2 => $questionIndex22,
            ],
        ];

        return [
            [$sessionQuestion, 2, 11],
        ];
    }
}

