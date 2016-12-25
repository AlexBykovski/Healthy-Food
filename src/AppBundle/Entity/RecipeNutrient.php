<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RecipeNutrient
 *
 * @ORM\Table(name="recipe_nutrient")
 * @ORM\Entity
 */
class RecipeNutrient
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Название не должно быть пустым", groups={"recipe_nutrient_create"})
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="count", type="float", nullable=false)
     */
    private $count;

    /**
     * Many RecipeNutrients have One Recipe.
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="nutrients")
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id")
     */
    private $recipe;

    public static $nutrientTypes = ["белки", "жиры", "углеводы", "калории"];

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param float $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * @return mixed
     */
    public function getRecipe()
    {
        return $this->recipe;
    }

    /**
     * @param mixed $recipe
     */
    public function setRecipe($recipe)
    {
        $this->recipe = $recipe;
    }
}