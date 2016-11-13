<?php
/**
 * User: iyurin
 * Date: 04.10.16
 * Time: 23:29
 */

namespace VIS\Bundle\CoreBundle\Component\ORM\Extension;

/**
 * Trait DeleteAtTrait
 *
 * Adds deleted date property and special methods
 *
 * Class DeleteAtTrait
 * @package VIS\Bundle\CoreBundle\Component\ORM\Extension
 */
trait DeleteAtTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delete_at", type="datetime", nullable=true)
     * @Serializer\Groups({"product.deleteAt", "store.deleteAt", "user.deleteAt", "subCategory.deleteAt", "productType.deleteAt",
     *     "mediaBox.deleteAt", "mailRequest.deleteAt", "category.deleteAt",})
     * @Serializer\SerializedName("deleteAt")
     */
    protected $deleteAt;

    /**
     * @return \DateTime
     */
    public function getDeleteAt()
    {
        return $this->deleteAt;
    }

    /**
     * @param \DateTime $deleteAt
     */
    public function setDeleteAt($deleteAt)
    {
        $this->deleteAt = $deleteAt;
    }
}