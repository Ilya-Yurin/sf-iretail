<?php
/**
 * User: iyurin
 * Date: 04.10.16
 * Time: 23:13
 */

namespace VIS\Bundle\CoreBundle\Entity;

/**
 * Class AbstractEntity
 * @package VIS\Bundle\CoreBundle\Entity
 */
class AbstractEntity implements \Serializable
{
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
     * @inheritDoc
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            ) = unserialize($serialized);
    }
}