<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CocktailRepository")
 */
class Cocktail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Ingredient",
     *     mappedBy="cocktails"
     * )
     */
    private $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return String
     */
    public function getName(): String
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName(String $name): void
    {
        $this->name = $name;
    }

    /**
     * Returns an array of the ingredients names
     *
     */
    public function getIngredientsNamesArray() : array
    {
        $arrayIngredientsNames = [];
        if($this->ingredients){
            foreach ($this->ingredients as $ingredient){
                $arrayIngredientsNames[] = $ingredient->getName();
            }
        }
        return $arrayIngredientsNames;
    }

    /**
     * Assign a ingredient to a cocktail
     *
     * @param Ingredient $ingredient the ingredient to assign
     */
    public function addIngredient(Ingredient $ingredient)
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients[] = $ingredient;
            $ingredient->addCocktail($this);
        }
    }

    /**
     * Converting Cocktail object to array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'ingredients' => $this->getIngredientsNamesArray()
        ];
    }
}
