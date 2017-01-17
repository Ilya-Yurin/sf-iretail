<?php
/**
 * User: iyurin
 * Date: 04.10.16
 * Time: 23:24
 */

namespace VIS\Bundle\CoreBundle\Entity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;
/**
 * Class User
 * @package VIS\Bundle\CoreBundle\Entity
 * @ORM\Entity(repositoryClass="VIS\Bundle\CoreBundle\EntityRepository\User")
 * @ORM\Table(name="users")
 *
 */
class User implements AdvancedUserInterface, \Serializable
{
    const
        STATUS_ACTIVE = 1,
        STATUS_INACTIVE = 0;

    const
        TYPE_USER = 0,
        TYPE_MANAGER_ADMIN = 1,
        TYPE_SUPER_ADMIN = 5;

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
     *                  VIS\Bundle\CoreBundle\Entity\User::TYPE_SUPER_ADMIN
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
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
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
     * @return string
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
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    /**
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
            ) = unserialize($serialized);
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
        if($this->status == User::STATUS_ACTIVE){
            return true;
        }
        return false;
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }


    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}