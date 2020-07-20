<?php

namespace App\DataFixtures;

use App\Entity\MCQuestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class MCQuestionFixtures extends Fixture implements DependentFixtureInterface
{
    const QUEST_ALGO = 'quest_algo';
    const QUEST_ALGO_2 = 'quest_algo_2';
    const QUEST_PHP = 'quest_php';
    const QUEST_POO = 'quest_poo';
    const QUEST_POO_2 = 'quest_poo_2';
    const QUEST_POO_3 = 'quest_poo_3';
    const QUEST_SQL = 'quest_sql';
    const QUEST_JS = 'quest_js';

    public function load(ObjectManager $manager)
    {
        $algorithme = $this->createTextQuestion($this->getReference(CategoryFixtures::CAT_ALGO), 'Algorithme', '<p>Qu\'est ce que l\'algorithme</p>', 1);
        $this->setReference(self::QUEST_ALGO, $algorithme);
        $manager->persist($algorithme);
        $algorithme_2 = $this->createTextQuestion($this->getReference(CategoryFixtures::CAT_ALGO), 'Algorithme Dijkstra', '<p>Qu\'est-ce que l\'algorithme de Dijkstra ?</p>', 2);
        $this->setReference(self::QUEST_ALGO_2, $algorithme_2);
        $manager->persist($algorithme_2);
        $php = $this->createTextQuestion($this->getReference(CategoryFixtures::CAT_PHP), 'PHP', '<p>Pour afficher simplement un valeur en PHP,', 1);
        $this->setReference(self::QUEST_PHP, $php);
        $manager->persist($php);
        $poo = $this->createTextQuestion($this->getReference(CategoryFixtures::CAT_POO), 'POO', '<p>En OO, le terme "design patterns" se traduit par </p>', 1);
        $this->setReference(self::QUEST_POO, $poo);
        $manager->persist($poo);
        $poo_2 = $this->createTextQuestion($this->getReference(CategoryFixtures::CAT_POO), 'POO', '<p>En JavaScript ou PHP, comment instancier un objet à partir d\'une classe ?</p>', 1);
        $this->setReference(self::QUEST_POO_2, $poo_2);
        $manager->persist($poo_2);
        $poo_3 = $this->createTextQuestion($this->getReference(CategoryFixtures::CAT_POO), 'POO', '<p>Qu\'est-ce qu\'une "super classe" ?</p>', 1);
        $this->setReference(self::QUEST_POO_3, $poo_3);
        $manager->persist($poo_3);
        $sql = $this->createTextQuestion($this->getReference(CategoryFixtures::CAT_SQL), 'SQL', '<p>Applique moi le SQL</p>', 2);
        $this->setReference(self::QUEST_SQL, $sql);
        $manager->persist($sql);
        $sql = $this->createTextQuestion($this->getReference(CategoryFixtures::CAT_SQL), 'SQL', '<p> Que fait le SQL</p>', 1);
        $this->setReference(self::QUEST_SQL, $sql);
        $manager->persist($sql);
        $javascript = $this->createTextQuestion($this->getReference(CategoryFixtures::CAT_JAVASCRIPT), 'javascript', '<p>Qu\'est ce que le javascript</p>', 1);
        $this->setReference(self::QUEST_JS, $javascript);
        $manager->persist($javascript);
        $manager->flush();
    }

    protected function createTextQuestion($category, string $label, string $text, int $position): MCQuestion
    {
        $mcquestion = new MCQuestion();
        $mcquestion
            ->setCategory($category)
            ->setLabel($label)
            ->setText($text)
            ->setPosition($position);

        return $mcquestion;
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
