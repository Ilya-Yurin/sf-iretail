<?php
/**
 * User: iyurin
 * Date: 04.10.16
 * Time: 23:24
 */

namespace VIS\Bundle\CoreBundle\Entity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
/**
 * Class User
 * @package VIS\Bundle\CoreBundle\Entity
 * @ORM\Entity(repositoryClass="VIS\Bundle\CoreBundle\EntityRepository\User")
 * @ORM\Table(name="users")
 *
 */
class User extends AbstractEntity implements AdvancedUserInterface
{
    const
        STATUS_ACTIVE = 1,
        STATUS_INACTIVE = 0;

    const
        TYPE_USER = 0,
        TYPE_MANAGER_ADMIN = 1,
        TYPE_ADMIN = 5;

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Serializer\Groups({"user.id", "session_data", "list"})
     * @Serializer\SerializedName("id")
     */
    protected $id;

    /**
     * @var string $emailAddress
     *
     * @ORM\Column(name="email", type="string", length=100)
     *
     * @Assert\NotBlank(groups={"create", "update", "profile-email"})
     * @Assert\Length(max="100", groups={"create", "update", "profile-email"})
     * @Assert\Email(groups={"create", "update", "profile-email"})
     *
     * @Serializer\Groups({"user.email", "session_data", "list"})
     * @Serializer\SerializedName("email")
     */
    protected $email;

    /**
     * @var string $username
     *
     * @ORM\Column(name="username", type="string", length=256)
     *
     * @Assert\Length(max="256")
     *
     * @Serializer\Groups({"user.username", "session_data"})
     * @Serializer\SerializedName("username")
     */
    protected $username;

    /**
     * @var string $firstName
     *
     * @ORM\Column(name="first_name", type="string", length=256)
     *
     * @Assert\NotBlank(groups={"create", "update", "profile"}, message="The First Name is blank or invalid")
     * @Assert\Length(max="256", groups={"create", "update", "profile"})
     *
     * @Serializer\Groups({"user.firstName", "session_data", "list"})
     * @Serializer\SerializedName("firstName")
     */
    protected $firstName;

    /**
     * @var string $lastName
     *
     * @ORM\Column(name="last_name", type="string", length=256)
     *
     * @Assert\NotBlank(groups={"create", "update", "profile"}, message="The Last Name is blank or invalid")
     * @Assert\Length(max="256", groups={"create", "update", "profile"})
     *
     * @Serializer\Groups({"user.lastName", "session_data", "list"})
     * @Serializer\SerializedName("lastName")
     */
    protected $lastName;

    /**
     * @var string $middleName
     *
     * @ORM\Column(name="middle_name", type="string", length=256)
     *
     * @Assert\Length(max="256", groups={"create", "update", "profile"})
     *
     * @Serializer\Groups({"user.middleName", "session_data", "list"})
     * @Serializer\SerializedName("middleName")
     */
    protected $middleName;

    /**
     * @var string $phone
     *
     * @ORM\Column(name="phone", type="string", length=256)
     *
     * @Assert\Length(max="256", groups={"create", "update", "profile"})
     *
     * @Serializer\Groups({"user.phone", "session_data", "list"})
     * @Serializer\SerializedName("phone")
     */
    protected $phone;

    /**
     * @var string $birthday
     *
     * @ORM\Column(name="birthday", type="datetime")
     *
     * @Serializer\Groups({"user.birthday", "session_data", "list"})
     * @Serializer\SerializedName("birthday")
     */
    protected $birthday;

    /**
     *
     * @ORM\Column(name="last_login", type="datetime", nullable=true)
     */
    protected $lastLogin;

    /**
     * @ORM\OneToMany(targetEntity="UserToken", mappedBy="user", cascade={"persist", "remove"})
     */
    protected $tokens;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=128)
     *
     * @Assert\NotBlank(groups={"create", "profile-password"})
     *
     * @Serializer\Groups({"user.password"})
     * @Serializer\SerializedName("password")
     */
    protected $password;

    /**
     * @ORM\Column(name="status", type="integer")
     *
     * @Assert\NotBlank(groups={"create", "update"})
     * @Assert\Choice(choices={
     *                  VIS\Bundle\CoreBundle\Entity\User::STATUS_ACTIVE,
     *                  VIS\Bundle\CoreBundle\Entity\User::STATUS_INACTIVE
     * }, groups={"create", "update"})
     *
     * @Serializer\Groups({"user.status", "list"})
     * @Serializer\SerializedName("status")
     */
    protected $status = self::STATUS_ACTIVE;

    /**
     * @ORM\Column(name="user_type", type="integer")
     *
     * @Assert\NotBlank(groups={"create", "update"})
     * @Assert\Choice(choices={
     *                  VIS\Bundle\CoreBundle\Entity\User::TYPE_USER,
     *                  VIS\Bundle\CoreBundle\Entity\User::TYPE_MANAGER_ADMIN,
     *                  VIS\Bundle\CoreBundle\Entity\User::TYPE_ADMIN
     * }, groups={"create", "update"})
     *
     * @Serializer\Groups({"user.userType", "session_data", "list"})
     * @Serializer\SerializedName("userType")
     */
    protected $userType;

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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /***
     * @param $userType
     * @return $this
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
        return $this;
    }

    /**
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * @param $middleName
     * @return $this
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param $birthday
     * @return $this
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * @param $lastLogin
     * @return $this
     */
    public function setLastLogin($lastLogin)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * @param UserToken $tokens
     * @return $this
     */
    public function setTokens(UserToken $tokens)
    {
        $this->tokens = $tokens;
        return $this;
    }

    /**
     * Add tokens
     *
     * @param \VIS\Bundle\CoreBundle\Entity\UserToken $tokens
     * @return User
     */
    public function addToken(UserToken $tokens)
    {
        $this->tokens[] = $tokens;

        return $this;
    }

    /**
     * Generate random password
     *
     * @param int $length
     * @return string
     */
    public function generatePassword($length = 10)
    {
        return substr(md5(uniqid()), 0, $length);
    }

    public function isAccountNonExpired()
    {
        // TODO: Implement isAccountNonExpired() method.
    }

    public function isAccountNonLocked()
    {
        // TODO: Implement isAccountNonLocked() method.
    }

    public function isCredentialsNonExpired()
    {
        // TODO: Implement isCredentialsNonExpired() method.
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return ($this->status === self::STATUS_ACTIVE);
    }

    /**
     * @param UserInterface $user
     * @return bool
     */
    public function equals(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }

    public function getRoles()
    {
        switch ($this->userType) {
            case self::TYPE_USER:
                return array('ROLE_USER');
            case self::TYPE_ADMIN:
                return array('ROLE_USER', 'ROLE_ADMIN');
            default:
                return array();
        }
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }


    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
        ));
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized);
    }
}