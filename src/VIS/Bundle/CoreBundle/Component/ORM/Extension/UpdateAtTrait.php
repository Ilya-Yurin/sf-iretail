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
trait UpdateAtTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_at", type="datetime", nullable=true)
     * @Serializer\Groups({"product.updateAt", "store.updateAt", "user.updateAt", "subCategory.updateAt", "productType.updateAt",
     *     "mediaBox.updateAt", "mailRequest.updateAt", "category.updateAt",})
     * @Serializer\SerializedName("updateAt")
     */
    protected $updateAt;

    /**
     * @return \DateTime
     */
    public function getUpdateAt()
    {
        return $this->updateAt;
    }

    /**
     * @param \DateTime $updateAt
     */
    public function setUpdateAt($updateAt)
    {
        $this->updateAt = $updateAt;
    }

}