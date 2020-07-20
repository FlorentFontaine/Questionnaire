<?php

namespace App\Controller\Front;

use App\Candidate\Candidate;
use App\Candidate\CandidateManager;
use App\Form\CandidateType;
use App\Manager\CategoryManager;
use App\Manager\PdfManager;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidateController extends AbstractController
{
    protected $candidateManager;
    protected $pdfManager;
    protected $categoryManager;

    public function __construct(CategoryManager $categoryManager, PdfManager $pdfManager,  CandidateManager $candidateManager)
    {
        $this->candidateManager = $candidateManager;
        $this->categoryManager = $categoryManager;
        $this->pdfManager = $pdfManager;
    }

    /**
     * @Route("/", name="candidate.index")
     */
    public function index(Request $request): Response
    {
        $candidate = new Candidate();
        $form = $this->createForm(CandidateType::class, $candidate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->candidateManager->createCandidate($form->getData());

            return $this->redirectToRoute('category.index');
        }
        $this->categoryManager->generateIndexAllCategories();

        return $this->render('candidate.html.twig',
            ['form' => $form->createView()]);
    }

    /**
     * @Route("/evaluation", name="candidate.pdf")
     */
    public function viewPdf(Request $request): Response
    {
        $name = $this->candidateManager->getDate().'_'.$this->candidateManager->getName();
        $infosCandidate = $this->candidateManager->getCandidate();

        $interview = $this->pdfManager->getQuestionsByCategory();
        $html = $this->renderView('interviewResult.html.twig', ['interview' => $interview, 'infosCandidate' => $infosCandidate]);

        return $this->pdfManager->generatePdf($html,$name);
    }
}
