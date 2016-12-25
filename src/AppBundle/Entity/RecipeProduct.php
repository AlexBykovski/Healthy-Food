<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RecipeProduct
 *
 * @ORM\Table(name="recipe_product")
 * @ORM\Entity
 */
class RecipeProduct
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
     * @Assert\NotBlank(message = "Название не должно быть пустым", groups={"recipe_product_create"})
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var float
     *
     * @ORM\Column(name="count", type="float", nullable=true)
     */
    private $count;

    /**
     * @var string
     *
     * @ORM\Column(name="measure", type="string", nullable=true)
     */
    private $measure;

    public static $measureTypes = ["кг", "гр", "шт", "мг", "щепотка", "ст. ложка", "ч. ложка", "л", "мл",];

    /**
     * Many RecipeProducts have One Recipe.
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="products")
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id")
     */
    private $recipe;

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
     * @return string
     */
    public function getMeasure()
    {
        return $this->measure;
    }

    /**
     * @param string $measure
     */
    public function setMeasure($measure)
    {
        $this->measure = $measure;
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