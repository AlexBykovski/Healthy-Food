<?php

namespace AppBundle\Entity;

use AppBundle\Entity\DietAdditionalInformation;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="user_email", columns={"email"})})
 * @ORM\Entity
 *
 * @UniqueEntity(
 *     "email",
 *     message="Этот email уже зарегистрирован.",
 * )
 */
class User implements UserInterface
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
     * @Assert\NotBlank(message = "Не введено имя", groups={"registration"})
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Не введена фамилия", groups={"registration"})
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Не введена электронная почта", groups={"registration"})
     * @Assert\Email(message = "Не верный формат", groups={"registration"})
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    protected $email;

    /**
     *
     * @var string
     *
     * @Assert\NotBlank(message = "Не введен пароль", groups={"registration"})
     * @Assert\Length(
     *      min = 8,
     *      minMessage = "Пароль должен состоять минимум из 8 знаков",
     *      groups={"registration"}
     * )
     *
     * @ORM\Column(name="password", type="string", length=128, nullable=false)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="forgot_password_key", type="string", length=128, nullable=true)
     */
    private $forgotPasswordKey;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var float
     *
     * @Assert\NotBlank(message = "Не введён рост", groups={"registration"})
     *
     * @Assert\Regex(
     *     pattern="/^([3-9]\d)|([1-2]\d{2}(,\d)?)$/",
     *     message="Рост должен быть целым или десятичным числом с одной цифрой после запятой (пр. 167 или 155,4)",
     *     groups={"registration"}
     * )
     *
     * @ORM\Column(name="height", type="float", length=255, nullable=false)
     */
    private $height;

    /**
     * @var float
     *
     * @Assert\NotBlank(message = "Не введён вес")
     *
     * @Assert\Regex(
     *     pattern="/^[1-9]\d\d?(,\d)?$/",
     *     message="Вес должен быть целым или десятичным числом с одной цифрой после запятой (пр. 67 или 55,4)",
     *     groups={"registration"}
     * )
     *
     * @ORM\Column(name="weight", type="float", length=255, nullable=false)
     */
    private $weight;

    /**
     * @var boolean
     *
     * @Assert\NotBlank(message = "Не выбран пол")
     *
     * @ORM\Column(name="gender", type="boolean", length=255, nullable=false)
     */
    private $gender;

    /**
     * One User has One DietAdditionalInformation.
     * @ORM\OneToOne(targetEntity="DietAdditionalInformation", cascade={"persist"})
     * @ORM\JoinColumn(name="diet_additional_information_id", referencedColumnName="id")
     */
    private $dietAdditionalInformation;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->salt = md5(uniqid(null, true));
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
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string
     */
    public function getForgotPasswordKey()
    {
        return $this->forgotPasswordKey;
    }

    /**
     * @param string $forgotPasswordKey
     */
    public function setForgotPasswordKey($forgotPasswordKey)
    {
        $this->forgotPasswordKey = $forgotPasswordKey;
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
     * @return float
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param float $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return float
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param float $weight
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;
    }

    /**
     * @return boolean
     */
    public function isGender()
    {
        return $this->gender;
    }

    /**
     * @param boolean $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getDietAdditionalInformation()
    {
        return $this->dietAdditionalInformation;
    }

    /**
     * @param mixed $dietAdditionalInformation
     */
    public function setDietAdditionalInformation($dietAdditionalInformation)
    {
        $this->dietAdditionalInformation = $dietAdditionalInformation;
    }

    /**
     *
     */
    public function eraseCredentials()
    {
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }
}
