<?php
/**
 * User: iyurin
 * Date: 04.10.16
 * Time: 23:29
 */

namespace VIS\Bundle\CoreBundle\Component\ORM\Extension;

/**
 * Trait UpdateAtTrait
 *
 * Adds updated date property and special methods
 *
 * Class UpdateAtTrait
 * @package VIS\Bundle\CoreBundle\Component\ORM\Extension
 */
trait UpdatedAtTrait
{
    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     *
     * @Serializer\Groups({"user.updatedAt", "session_data"})
     * @Serializer\SerializedName("updatedAt")
     */
    protected $updatedAt;

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}