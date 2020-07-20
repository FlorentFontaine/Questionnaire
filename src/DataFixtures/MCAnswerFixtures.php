<?php

namespace App\DataFixtures;

use App\Entity\MCAnswer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MCAnswerFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $algorithme = $this->createMCAnswers('Une méthode décrite pas à pas', $this->getReference(MCQuestionFixtures::QUEST_ALGO), true);
        $manager->persist($algorithme);
        $algorithme = $this->createMCAnswers('Un problème de décision', $this->getReference(MCQuestionFixtures::QUEST_ALGO));
        $manager->persist($algorithme);
        $algorithme = $this->createMCAnswers('Un langage de programmation', $this->getReference(MCQuestionFixtures::QUEST_ALGO));
        $manager->persist($algorithme);
        $algorithme = $this->createMCAnswers('Un code numérique', $this->getReference(MCQuestionFixtures::QUEST_ALGO));
        $manager->persist($algorithme);
        $algorithme_2 = $this->createMCAnswers('Le meilleur algorithme connu jusqu\'à présent pour calculer le plus court chemin issu d\'un sommet donné dans un graphe', $this->getReference(MCQuestionFixtures::QUEST_ALGO_2), true);
        $manager->persist($algorithme_2);
        $algorithme_2 = $this->createMCAnswers('Le meilleur algorithme connu jusqu\'à présent pour calculer le plus court chemin entre 2 sommets donnés dans un graphe', $this->getReference(MCQuestionFixtures::QUEST_ALGO_2), true);
        $manager->persist($algorithme_2);
        $algorithme_2 = $this->createMCAnswers('Le meilleur algorithme connu jusqu\'a présent pour calculer le plus court chemin entre 2 sommets quelconques dans un graphe', $this->getReference(MCQuestionFixtures::QUEST_ALGO_2));
        $manager->persist($algorithme_2);
        $algorithme_2 = $this->createMCAnswers('Un algorithme tel que si son exécution prend de l\'ordre d\'1 ms sur un graphe de 10 sommets, son exécution sur un graphe de 1000 sommets prendra de l\'ordre de 1s', $this->getReference(MCQuestionFixtures::QUEST_ALGO_2));
        $manager->persist($algorithme_2);
        $php = $this->createMCAnswers('Echo', $this->getReference(MCQuestionFixtures::QUEST_PHP), true);
        $manager->persist($php);
        $php = $this->createMCAnswers('Print', $this->getReference(MCQuestionFixtures::QUEST_PHP));
        $manager->persist($php);
        $php = $this->createMCAnswers('Les deux instructions sont équivalentes', $this->getReference(MCQuestionFixtures::QUEST_PHP));
        $manager->persist($php);
        $poo = $this->createMCAnswers('entités graphiques', $this->getReference(MCQuestionFixtures::QUEST_POO));
        $manager->persist($poo);
        $poo = $this->createMCAnswers('fenêtres objets', $this->getReference(MCQuestionFixtures::QUEST_POO));
        $manager->persist($poo);
        $poo = $this->createMCAnswers('dessins de caractères', $this->getReference(MCQuestionFixtures::QUEST_POO), true);
        $manager->persist($poo);
        $poo_2 = $this->createMCAnswers('patrons de conception', $this->getReference(MCQuestionFixtures::QUEST_POO_2));
        $manager->persist($poo_2);
        $poo_2 = $this->createMCAnswers('instanceof', $this->getReference(MCQuestionFixtures::QUEST_POO_2));
        $manager->persist($poo_2);
        $poo_2 = $this->createMCAnswers('new', $this->getReference(MCQuestionFixtures::QUEST_POO_2), true);
        $manager->persist($poo_2);
        $poo_3 = $this->createMCAnswers('une classe "main"', $this->getReference(MCQuestionFixtures::QUEST_POO_3));
        $manager->persist($poo_3);
        $poo_3 = $this->createMCAnswers('une classe mère', $this->getReference(MCQuestionFixtures::QUEST_POO_3), true);
        $manager->persist($poo_3);
        $poo_3 = $this->createMCAnswers('une classe possédant des privilèges', $this->getReference(MCQuestionFixtures::QUEST_POO_3));
        $manager->persist($poo_3);
        $manager->flush();
    }

    protected function createMCAnswers(string $answer, $mcquestion, bool $valid = false): MCAnswer
    {
        $mcanswers = new MCAnswer();
        $mcanswers
            ->setText($answer)
            ->setValid($valid)
            ->setMCQuestion($mcquestion);

        return $mcanswers;
    }

    public function getDependencies()
    {
        return [
            MCQuestionFixtures::class,
        ];
    }
}
