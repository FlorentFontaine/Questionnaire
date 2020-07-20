<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CAT_ALGO = 'cat_algorithme';
    const CAT_PHP = 'cat_php';
    const CAT_POO = 'cat_poo';
    const CAT_SQL = 'cat_sql';
    const CAT_JAVASCRIPT = 'cat_javascript';

    public function load(ObjectManager $manager)
    {
        $algorithme = $this->createCategory('Algorithme', 1);
        $this->setReference(self::CAT_ALGO, $algorithme);
        $manager->persist($algorithme);
        $php = $this->createCategory('PHP', 2);
        $this->setReference(self::CAT_PHP, $php);
        $manager->persist($php);
        $poo = $this->createCategory('POO', 3);
        $this->setReference(self::CAT_POO, $poo);
        $manager->persist($poo);
        $javascript = $this->createCategory('Javascript', 4);
        $this->setReference(self::CAT_JAVASCRIPT, $javascript);
        $manager->persist($javascript);
        $sql = $this->createCategory('SQL', 5);
        $this->setReference(self::CAT_SQL, $sql);
        $manager->persist($sql);
        $manager->flush();
    }

    protected function createCategory(string $label, string $position): Category
    {
        $category = new Category();
        $category
            ->setLabel($label)
            ->setPosition($position);

        return $category;
    }
}
