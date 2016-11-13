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
trait CreateAtTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime", nullable=true)
     * @Serializer\Groups({"product.createAt", "store.createAt", "user.createAt", "subCategory.createAt", "productType.createAt",
     *     "mediaBox.createAt", "mailRequest.createAt", "category.createAt",})
     * @Serializer\SerializedName("createAt")
     */
    protected $createAt;

    /**
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * @param \DateTime $createAt
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;
    }

}