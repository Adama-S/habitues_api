<?php

namespace App\Repository;

use App\Entity\Ingredient;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ingredient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ingredient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ingredient[]    findAll()
 * @method Ingredient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IngredientRepository extends ServiceEntityRepository {

    private $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, Ingredient::class);
        $this->manager = $manager;
    }
    public function getIngredientsByName($name) :array
    {
        return $this->createQueryBuilder("i")
            ->select("i")
            ->where("i.name LIKE :ingredientName")
            ->setParameter("ingredientName", "%".$name."%")
            ->getQuery()
            ->getResult();
    }

    public function saveIngredient($name)
    {
        $ingredient = $this->findOneBy(["name" => $name]);

        if(!$ingredient){
            $newIngredient = new Ingredient($name);

            $this->manager->persist($newIngredient);
            $this->manager->flush();
        }
    }
}