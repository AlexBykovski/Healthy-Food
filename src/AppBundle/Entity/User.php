<?php

namespace AppBundle\Entity;

use AppBundle\Entity\DietAdditionalInformation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="user_email", columns={"email"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 *
 * @UniqueEntity(
 *     "email",
 *     message="Этот email уже зарегистрирован.",
 * )
 *
 * @HasLifecycleCallbacks
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
     * @Assert\NotBlank(message = "Не введено имя", groups={"registration", "edit_profile"})
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Не введена фамилия", groups={"registration", "edit_profile"})
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @Assert\NotBlank(message = "Не введена электронная почта", groups={"registration", "edit_profile"})
     * @Assert\Email(message = "Не верный формат", groups={"registration", "edit_profile"})
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
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

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
     * @Assert\File(maxSize="10000000")
     */
    private $filePhoto;

    /**
     * @var float
     *
     * @Assert\NotBlank(message = "Не введён рост", groups={"registration", "edit_profile"})
     *
     * @Assert\Regex(
     *     pattern="/^([3-9]\d)|([1-2]\d{2}(,\d)?)$/",
     *     message="Рост должен быть положительным целым или десятичным числом с одной цифрой после запятой (пр. 167 или 155,4)",
     *     groups={"registration", "edit_profile"}
     * )
     *
     * @ORM\Column(name="height", type="float", length=255, nullable=false)
     */
    private $height;

    /**
     * @var float
     *
     * @Assert\NotBlank(message = "Не введён вес", groups={"registration", "edit_profile"})
     *
     * @Assert\Regex(
     *     pattern="/^[1-9]\d\d?(,\d)?$/",
     *     message="Вес должен быть положительным целым или десятичным числом с одной цифрой после запятой (пр. 67 или 55,4)",
     *     groups={"registration", "edit_profile"}
     * )
     *
     * @ORM\Column(name="weight", type="float", length=255, nullable=false)
     */
    private $weight;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gender", type="boolean", length=255, nullable=false, options={"default" : false})
     */
    private $gender = false;

    /**
     * @Assert\Valid
     *
     * One User has One DietAdditionalInformation.
     * @ORM\OneToOne(targetEntity="DietAdditionalInformation", cascade={"persist"})
     * @ORM\JoinColumn(name="diet_additional_information_id", referencedColumnName="id")
     */
    private $dietAdditionalInformation;

    /**
     * @ORM\ManyToMany(targetEntity="Role", inversedBy="users")
     * @ORM\JoinTable(name="users_roles")
     */
    private $userRoles;

    /**
     * One User has Many Eatings.
     * @ORM\OneToMany(targetEntity="Eating", mappedBy="user", cascade={"persist"})
     */
    private $eatings;

    /**
     * One User has Many Notifications.
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="user")
     */
    private $notifications;

    /**
     * @var integer
     *
     * @Assert\NotBlank(message = "Не введён возраст", groups={"registration", "edit_profile"})
     *
     * @Assert\Regex(
     *     pattern="/^[1-9]\d$/",
     *     message="Возраст должен быть положительным целым (пр. 20)",
     *     groups={"registration", "edit_profile"}
     * )
     *
     * @ORM\Column(name="age", type="integer", length=255, nullable=false)
     */
    private $age;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->salt = md5(uniqid(null, true));
        $this->userRoles = new ArrayCollection();
        $this->eatings = new ArrayCollection();
        $this->notifications = new ArrayCollection();
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

    public function getRoles()
    {
        $roles = [];

        foreach($this->userRoles as $role){
            $roles[] = $role->getRole();
        }

        return $roles;
    }

    /**
     * @return mixed
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * @param mixed $userRoles
     */
    public function setUserRoles($userRoles)
    {
        $this->userRoles = $userRoles;
    }

    /**
     * @param Role $role
     */
    public function addUserRole(Role $role)
    {
        $this->userRoles->add($role);
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function getEatings()
    {
        return $this->eatings;
    }

    /**
     * @param mixed $eatings
     */
    public function setEatings($eatings)
    {
        $this->eatings = $eatings;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return UploadedFile
     */
    public function getFilePhoto()
    {
        return $this->filePhoto;
    }

    /**
     * @param UploadedFile $filePhoto
     */
    public function setFilePhoto($filePhoto)
    {
        $this->filePhoto = $filePhoto;
    }

    protected function getUploadDir() {
        return $_SERVER['DOCUMENT_ROOT']. DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'profile-images';
    }

    public function upload()
    {
        if (!is_null($this->getFilePhoto())) {
            $this->getFilePhoto()->move(
                $this->getUploadDir(), $this->getFilePhoto()->getClientOriginalName()
            );

            $this->setPhoto('images' . DIRECTORY_SEPARATOR . 'profile-images' . DIRECTORY_SEPARATOR .
                $this->getFilePhoto()->getClientOriginalName());
            $this->setFilePhoto(null);
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function lifecycleFileUpload()
    {
        $this->upload();
    }

    public function refreshUpdated()
    {
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param mixed $notifications
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * @param Notification $notification
     */
    public function addNotifications(Notification $notification)
    {
        return $this->notifications->add($notification);
    }

    /**
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * @param int $age
     */
    public function setAge($age)
    {
        $this->age = $age;
    }
}
