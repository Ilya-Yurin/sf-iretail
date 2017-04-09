<?php
/**
 * User: iyurin
 * Date: 13.11.16
 * Time: 12:58
 */

namespace VIS\Bundle\CoreBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_tokens")
 */
class UserToken
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @ORM\Column(name="token", type="string", length=255, unique=true)
     * @var string $name
     */
    protected $token;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="tokens")
     * @Assert\NotBlank()
     **/
    protected $user;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     **/
    protected $created_at;

    /**
     * @return integer The id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string token.
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $value token.
     */
    public function setToken($value)
    {
        $this->token = $value;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $value
     */
    public function setUser($value)
    {
        $this->user = $value;
    }

    /**
     * @return string created at.
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param string $value created at.
     */
    public function setCreatedAt($value)
    {
        $this->created_at = $value;
    }
}