<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RecipeStep
 *
 * @ORM\Table(name="recipe_step")
 * @ORM\Entity
 */
class RecipeStep
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
     * @Assert\NotBlank(message = "Описание не должно быть пустым", groups={"recipe_step_create"})
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    private $description;

    /**
     * Many RecipeSteps have One Recipe.
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="steps")
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
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return Recipe
     */
    public function getRecipe()
    {
        return $this->recipe;
    }

    /**
     * @param Recipe $recipe
     */
    public function setRecipe($recipe)
    {
        $this->recipe = $recipe;
    }
}