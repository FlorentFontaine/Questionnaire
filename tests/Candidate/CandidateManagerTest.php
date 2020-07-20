<?php

use App\Candidate\Candidate;
use App\Candidate\CandidateManager;
use App\Entity\MCAnswer;
use App\QuestionIndex\QuestionIndex;
use App\Repository\CategoryRepository;
use App\Repository\MCAnswerRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CandidateManagerTest extends TestCase
{
    public $candidateManagerTest;
    public $session;
    public $questionIndex;
    public $candidate;
    public $mCAnswerRepository;
    public $categoryRepository;

    protected function setUp(): void
    {
        $this->questionIndex = $this->createMock(QuestionIndex::class);
        $this->candidate = $this->createMock(Candidate::class);
        $this->mCAnswerRepository = $this->createMock(MCAnswerRepository::class);
        $this->session = $this->createMock(SessionInterface::class);
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        $this->candidateManagerTest = new CandidateManager($this->questionIndex,$this->candidate,$this->mCAnswerRepository, $this->session, $this->categoryRepository);
    }

    public function testCreateCandidate()
    {
        $candidate = $this->createMock(Candidate::class);

        $this->session
            ->expects($this->once())
            ->method('set')
            ->willReturn($candidate);

        $this->candidateManagerTest->createCandidate($candidate);
    }

    public function testGetCandidate()
    {
        $candidate = $this->createMock(Candidate::class);

        $this->session
            ->expects($this->once())
            ->method('get')
            ->willReturn($candidate);

        $this->candidateManagerTest->getCandidate();

    }

    public function testGetDate()
    {
        $this->candidate
            ->expects($this->once())
            ->method('getCreatedAt')
            ->willReturn('20200-10-10');

        $date = $this->candidateManagerTest->getDate();
        $this->assertSame('20200-10-10', $date);

    }

    public function testGetNameSession()
    {
        $candidate = $this->createMock(Candidate::class);

        $this->session
            ->method('get')
            ->willReturn($candidate);

        $candidate
            ->expects($this->once())
            ->method('getLastName')
            ->willReturn('fontaine');

        $name = $this->candidateManagerTest->getNameSession();
        $this->assertSame('fontaine', $name);
    }

    /**
     * @dataProvider  provideGenerateIndexAnswers
     */
    public function testGenerateIndexAnswers($mcAnswers, $type)
    {
        $questionIndex1 = $this->createMock(QuestionIndex::class);
        $questionIndex2 = $this->createMock(QuestionIndex::class);
        $questionIndex3 = $this->createMock(QuestionIndex::class);
        $questionIndex4 = $this->createMock(QuestionIndex::class);

        $questionIndex = [
            1 => [
                1 => $questionIndex1,
                2 => $questionIndex2,
            ],
        ];

        $questionIndex3
            ->method('getId')
            ->willReturn(1);

        $questionIndex4
            ->method('getType')
            ->willReturn($type);

        $this->session
            ->method('get')
            ->willReturn($questionIndex);

        $this->session
            ->expects($this->once())
            ->method('set')
            ->willReturn($questionIndex);

        $this->candidateManagerTest->GenerateIndexAnswers($mcAnswers, 1);
    }


    public function provideGenerateIndexAnswers(): array
    {
        $mcAnswers = [
            1 => [
                1 => 1,
                2 => 2,
            ],
        ];


        $mcAnswers1 = [
            1 => 'test'
        ];


        return [
            [$mcAnswers, 'QCM'],
            [$mcAnswers1, 'QT']
        ];
    }

}
