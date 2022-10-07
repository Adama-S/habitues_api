<?php

namespace App\Repository;

use App\Entity\Cocktail;

use App\Entity\Ingredient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Cocktail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cocktail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cocktail[]    findAll()
 * @method Cocktail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CocktailRepository extends ServiceEntityRepository {

    private $manager;

    public function __construct
    (
        ManagerRegistry $registry,
        EntityManagerInterface $manager
    )
    {
        parent::__construct($registry, Cocktail::class);
        $this->manager = $manager;
        $this->ingredientRepository = $this->manager->getRepository(Ingredient::class);
    }

    public function getCocktailsByName($name) :array
    {
        return $this->createQueryBuilder("c")
            ->select("c")
            ->where("c.name LIKE :cocktailName")
            ->setParameter("cocktailName", "%".$name."%")
            ->getQuery()
            ->getResult();
    }

    public function getCocktailsByIngredientId($ingredientId) :array
    {
       return $this->createQueryBuilder("c")
            ->select("c")
            ->join("c.ingredients", "ingr")
            ->where("ingr = :ingredientId")
            ->setParameter("ingredientId", $ingredientId)
            ->getQuery()
            ->getResult();
    }

    public function saveCocktail($name, $ingredientsIds)
    {
        $cocktail = $this->findOneBy(["name" => $name]);

        if(!$cocktail){
            $newCocktail = new Cocktail();

            $newCocktail
                ->setName($name);

            foreach ($ingredientsIds as $ingredientId) {
                $ingredient = $this->ingredientRepository->findOneBy(["id" => $ingredientId]);
                $newCocktail->addIngredient($ingredient);
            }

            $this->manager->persist($newCocktail);
            $this->manager->flush();
        }
    }
}