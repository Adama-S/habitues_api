<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IngredientRepository")
 */
class Ingredient
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
     *     targetEntity="App\Entity\Cocktail",
     *     inversedBy="ingredients"
     * )
     * @ORM\JoinTable(name="cocktails_ingredients")
     */
    private $cocktails;

    public function __construct(String $name)
    {
        $this->name = $name;
        $this->cocktails = new ArrayCollection();
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
     * Assign a cocktail to a ingredient
     *
     * @param Cocktail $cocktail the cocktail to assign
     */
    public function addCocktail(Cocktail $cocktail)
    {
        if (!$this->cocktails->contains($cocktail)) {
            $this->cocktails[] = $cocktail;
            $cocktail->addIngredient($this);
        }
    }

    /**
     * Converting Ingredient object to array
     *
     * @return array
     */
    public function toArray() : array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName()
        ];
    }
}
