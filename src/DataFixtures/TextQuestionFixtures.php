<?php

namespace App\DataFixtures;

use App\Entity\TextQuestion;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TextQuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $algorithme = $this->createTextQuestion('pourquoi pas l\'algo', $this->getReference(CategoryFixtures::CAT_ALGO), 'Algorithme', 'Pourquoi l\'algorithme', 1);
        $manager->persist($algorithme);
        $php = $this->createTextQuestion('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', $this->getReference(CategoryFixtures::CAT_PHP), 'PHP', 'Pourquoi le PHP', 1);
        $manager->persist($php);
        $POO = $this->createTextQuestion('pourquoi pas la POO', $this->getReference(CategoryFixtures::CAT_POO), 'POO', 'Pourquoi le POO', 1);
        $manager->persist($POO);
        $SQL = $this->createTextQuestion('pourquoi pas le SQL', $this->getReference(CategoryFixtures::CAT_SQL), 'SQL', 'Pourquoi le SQL', 2);
        $manager->persist($SQL);
        $SQL = $this->createTextQuestion('parce que', $this->getReference(CategoryFixtures::CAT_SQL), 'SQL', 'Aimes-tu le SQL', 1);
        $manager->persist($SQL);
        $javascript = $this->createTextQuestion('pourquoi pas le java', $this->getReference(CategoryFixtures::CAT_JAVASCRIPT), 'javascript', 'Pourquoi le javascript', 1);
        $manager->persist($javascript);
        $manager->flush();
    }

    protected function createTextQuestion(string $answer, $category, string $label, string $text, int $position): TextQuestion
    {
        $textQuestion = new TextQuestion();
        $textQuestion
            ->setAnswer($answer)
            ->setCategory($category)
            ->setLabel($label)
            ->setText($text)
            ->setPosition($position);

        return $textQuestion;
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
