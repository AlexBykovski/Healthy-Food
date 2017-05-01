<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Eating
 *
 * @ORM\Table(name="eating")
 * @ORM\Entity
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EatingRepository")
 */
class Eating
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
     * Many Eatings have One User.
     * @ORM\ManyToOne(targetEntity="User", inversedBy="eatings")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Many Eatings have One Recipe.
     * @ORM\ManyToOne(targetEntity="Recipe", inversedBy="eatings")
     * @ORM\JoinColumn(name="recipe_id", referencedColumnName="id")
     */
    private $recipe;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var integer
     *
     * @ORM\Column(name="portions", type="integer", nullable=false)
     */
    private $portions;

    const BREAKFAST_START = 6;
    const BREAKFAST_END = 10;
    const LUNCH_START = 10;
    const LUNCH_END = 12;
    const DINNER_START = 12;
    const DINNER_END = 16;
    const AFTERNOON_SNACK_START = 16;
    const AFTERNOON_SNACK_END = 18;
    const SUPPER_START = 18;
    const SUPPER_END = 20;
    const SEC_SUPPER_START = 20;
    const SEC_SUPPER_END = 22;


    /**
     * Eating constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
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
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
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

    /**
     * @return DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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