<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Recipe
 *
 * @ORM\Table(name="recipe")
 * @ORM\Entity
 */
class Recipe
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
     * @ORM\Column(name="photo", type="string", nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="string", nullable=true)
     */
    private $time;

    /**
     * @var integer
     *
     * @ORM\Column(name="portions", type="integer", nullable=true)
     */
    private $portions;

    /**
     * @var string
     *
     * @ORM\Column(name="eating_type", type="string", nullable=true)
     */
    private $eatingType;

    /**
     * One Recipe has Many RecipeSteps.
     * @ORM\OneToMany(targetEntity="RecipeStep", mappedBy="recipe", cascade={"persist"})
     */
    private $steps;

    /**
     * One Recipe has Many RecipeProducts.
     * @ORM\OneToMany(targetEntity="RecipeProduct", mappedBy="recipe", cascade={"persist"})
     */
    private $products;

    /**
     * One Recipe has Many RecipeNutrients.
     * @ORM\OneToMany(targetEntity="RecipeNutrient", mappedBy="recipe", cascade={"persist"})
     */
    private $nutrients;

    public static $eatingTypes = ["завтрак", "второй завтрак", "обед", "полдник", "ужин", "второй ужин"];

    /**
     * Recipe constructor.
     */
    public function __construct()
    {
        $this->steps = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->nutrients = new ArrayCollection();
    }

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
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @param string $photo
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
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
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getEatingType()
    {
        return $this->eatingType;
    }

    /**
     * @param string $eatingType
     */
    public function setEatingType($eatingType)
    {
        $this->eatingType = $eatingType;
    }

    /**
     * @return mixed
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @param mixed $steps
     */
    public function setSteps($steps)
    {
        $this->steps = $steps;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param mixed $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * @return mixed
     */
    public function getNutrients()
    {
        return $this->nutrients;
    }

    /**
     * @param mixed $nutrients
     */
    public function setNutrients($nutrients)
    {
        $this->nutrients = $nutrients;
    }

    /**
     * @return int
     */
    public function getPortions()
    {
        return $this->portions;
    }

    /**
     * @param int $portions
     */
    public function setPortions($portions)
    {
        $this->portions = $portions;
    }
}