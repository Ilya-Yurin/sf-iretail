<?php

/**
 * User: iyurin
 * Date: 09.04.17
 * Time: 14:47
 */
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use VIS\Bundle\CoreBundle\Entity\User;
/**
 * Class LoadUserData
 * @package App\Bundle\CoreBundle\DataFixtures\ORM\User
 */
class LoadUserData implements FixtureInterface , OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $standartUser = new User();

        $encoder = $this->container->get('security.password_encoder');

        $standartUser
            ->setEmail('user@user.com')
            ->setFirstName('User')
            ->setLastName('Standard')
            ->setPassword($encoder->encodePassword($standartUser, 'user'))
            ->setUserType(User::TYPE_USER);

        $superAdmin = new User();
        $superAdmin
            ->setEmail('admin@admin.com')
            ->setFirstName('Admin')
            ->setLastName('Super')
            ->setPassword($encoder->encodePassword($superAdmin, 'admin'))
            ->setUserType(User::TYPE_ADMIN);

        $manager->persist($standartUser);
        $manager->persist($superAdmin);

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder() {
        return 1;
    }
}