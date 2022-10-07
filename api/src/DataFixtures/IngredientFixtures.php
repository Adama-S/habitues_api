<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class IngredientFixtures extends Fixture implements OrderedFixtureInterface
{
    public const MENTHE_REFERENCE = "menthe-ingredient";
    public const CITRON_VERT_REFERENCE = "citron-vert-ingredient";
    public const EAU_GAZEUSE_REFERENCE = "eau-gazeuse-ingredient";
    public const FRAISE_REFERENCE = "fraise-ingredient";
    public const JUS_ANANAS_REFERENCE = "jus-ananas-ingredient";

    public function load(ObjectManager $manager)
    {
        $menthe = new Ingredient('Menthe');
        $citronVert = new Ingredient('Citron vert');
        $eauGazeuse = new Ingredient('Eau Gazeuse');
        $fraise = new Ingredient('Fraise');
        $jusAnanas = new Ingredient('Jus ananas');

        $manager->persist($menthe);
        $manager->persist($citronVert);
        $manager->persist($eauGazeuse);
        $manager->persist($fraise);
        $manager->persist($jusAnanas);

        $this->addReference(
            self::MENTHE_REFERENCE,
            $menthe
        );
        $this->addReference(
            self::CITRON_VERT_REFERENCE,
            $citronVert
        );
        $this->addReference(
            self::EAU_GAZEUSE_REFERENCE,
            $eauGazeuse
        );
        $this->addReference(
            self::FRAISE_REFERENCE,
            $fraise
        );
        $this->addReference(
            self::JUS_ANANAS_REFERENCE,
            $jusAnanas
        );

        $manager->flush();
    }

    public function getOrder(): int
    {
        return 1;
    }
}