<?php

use App\Entity\AbstractQuestion;
use App\Entity\Category;
use App\Entity\MCQuestion;
use App\Entity\TextQuestion;
use App\Manager\PdfManager;
use App\Manager\QuestionManager;
use App\QuestionIndex\QuestionIndex;
use App\Repository\CategoryRepository;
use App\Repository\MCAnswerRepository;
use App\Repository\MCQuestionRepository;
use App\Repository\TextQuestionRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PdfManagerTest extends TestCase
{
    public $pdfManagerTest;
    public $session;
    public $questionManager;
    public $mCAnswerRepository;
    public $categoryRepository;
    public $textQuestionRepository;
    public $mCQuestionRepository;

    protected function setUp(): void
    {
        $this->mCAnswerRepository = $this->createMock(MCAnswerRepository::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->questionManager = $this->createMock(QuestionManager::class);
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        $this->textQuestionRepository = $this->createMock(TextQuestionRepository::class);
        $this->mCQuestionRepository = $this->createMock(MCQuestionRepository::class);
        $this->pdfManagerTest = new PdfManager($this->mCAnswerRepository, $this->session, $this->questionManager , $this->categoryRepository, $this->textQuestionRepository,$this->mCQuestionRepository);
    }

    /**
     * @dataProvider  provideGetQuestionsByCategory
     */
    public function testGetQuestionsByCategory($abstractQuestion)
    {
        $questionIndex1 = $this->createMock(QuestionIndex::class);
        $questionIndex2 = $this->createMock(QuestionIndex::class);
        $category1 = $this->createMock(Category::class);

        $category = [
                1 => $category1,
        ];


        $questionIndex = [
            1 => [
                1 => $questionIndex1,
                2 => $questionIndex2,
            ],
        ];

        $this->session
            ->method('get')
            ->willReturn($questionIndex);

        $this->session
            ->method('set')
            ->willReturn($questionIndex);

        $this->categoryRepository
            ->expects($this->once())
            ->method('findCategory')
            ->willReturn($category);

        $this->questionManager
            ->method('getQuestionByIndex')
            ->willReturn($abstractQuestion);


        $this->pdfManagerTest->getQuestionsByCategory();
    }

    public function testQuestionIndex()
    {
        $questionIndex1 = $this->createMock(QuestionIndex::class);
        $questionIndex2 = $this->createMock(QuestionIndex::class);

        $questionIndex = [
        1 => [
            1 => $questionIndex1,
            2 => $questionIndex2,
            ],
        ];

        $questionsByCategory = $this->pdfManagerTest->questionIndex($questionIndex);
        $this->assertInstanceOf(AbstractQuestion::class, $questionsByCategory[0] );
    }

    public function provideGetQuestionsByCategory(): array
    {
        $mCQuestion = $this->createMock(MCQuestion::class);
        $textQuestion = $this->createMock(TextQuestion::class);

        return [
            [$mCQuestion],
            [$textQuestion],
        ];
    }
}
