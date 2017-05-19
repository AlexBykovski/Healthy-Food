<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DietAdditionalInformation
 *
 * @ORM\Table(name="diet_additional_information")
 * @ORM\Entity
 */
class DietAdditionalInformation
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
     * @var integer
     *
     * @Assert\Range(
     *      min = 3,
     *      max = 6,
     *      minMessage = "Количество приёмов пищи не должно быть менее 3",
     *      maxMessage = "Количество приёмов пищи не должно быть более 6",
     *      groups={"registration", "edit_profile"})
     *
     * @ORM\Column(name="count_eating", type="integer", nullable=false, options={"default": 3})
     */
    private $countEating;

    /**
     * @var integer
     *
     * @Assert\Range(
     *      min = 0,
     *      max = 21,
     *      minMessage = "Количество тренировок в неделю не должно быть менее 0",
     *      maxMessage = "Количество тренировок в неделю не должно быть более 21",
     *      groups={"registration", "edit_profile"})
     *
     * @ORM\Column(name="count_training", type="integer", nullable=false, options={"default": 0})
     */
    private $countTraining;

    /**
     * @var integer
     *
     * @Assert\Range(
     *      min = 1,
     *      max = 3,
     *      minMessage = "Сложность тренировок не должна быть больше, либо равна 1",
     *      maxMessage = "Сложность тренировок не должна быть меньше, либо равна 3",
     *      groups={"registration", "edit_profile"})
     *
     * @ORM\Column(name="training_difficulty", type="integer", nullable=false, options={"default": 1})
     */
    private $trainingDifficulty;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Не выбрана цель", groups={"registration", "edit_profile"})
     *
     * @ORM\Column(name="purpose", type="string", nullable=false, options={"default": "Сбросить вес"})
     */
    private $purpose;

    /**
     * @var string
     *
     * @ORM\Column(name="information", type="text", nullable=true)
     */
    private $weightsANN;

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
     * @return int
     */
    public function getCountEating()
    {
        return $this->countEating;
    }

    /**
     * @param int $countEating
     */
    public function setCountEating($countEating)
    {
        $this->countEating = $countEating;
    }

    /**
     * @return int
     */
    public function getCountTraining()
    {
        return $this->countTraining;
    }

    /**
     * @param int $countTraining
     */
    public function setCountTraining($countTraining)
    {
        $this->countTraining = $countTraining;
    }

    /**
     * @return int
     */
    public function getTrainingDifficulty()
    {
        return $this->trainingDifficulty;
    }

    /**
     * @param int $trainingDifficulty
     */
    public function setTrainingDifficulty($trainingDifficulty)
    {
        $this->trainingDifficulty = $trainingDifficulty;
    }

    /**
     * @return string
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * @param string $purpose
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;
    }

    /**
     * @return string
     */
    public function getWeightsANN()
    {
        return $this->weightsANN;
    }

    /**
     * @param string $weightsANN
     */
    public function setWeightsANN($weightsANN)
    {
        $this->weightsANN = $weightsANN;
    }
}