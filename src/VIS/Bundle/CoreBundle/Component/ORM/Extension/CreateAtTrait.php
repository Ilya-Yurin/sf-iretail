<?php
/**
 * User: iyurin
 * Date: 04.10.16
 * Time: 23:28
 */

namespace VIS\Bundle\CoreBundle\Component\ORM\Extension;

/**
 * Trait CreateAtTrait
 *
 * Adds created date property and special methods
 *
 * Class CreateAtTrait
 * @package VIS\Bundle\CoreBundle\Component\ORM\Extension
 */
trait CreatedAtTrait
{
    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at", type="datetime")
     *
     * @Serializer\Groups({"user.createdAt", "session_data"})
     * @Serializer\SerializedName("createdAt")
     */
    protected $createdAt;

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}