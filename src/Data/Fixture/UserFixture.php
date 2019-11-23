<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace Api\Data\Fixture;

use App\Entity\ConfirmToken;
use App\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Zend\Crypt\Password\Bcrypt;

/**
 * Class UserFixture
 * @package Api\Data\Fixture
 */
class UserFixture extends AbstractFixture
{
    /**
     * @inheritdoc
     *
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        /** @var User $user */
        $user = (new User)
            ->setEmail('demo@example.com')
            ->setPassword((new Bcrypt)->create('secret'))
            ->setConfirmToken(ConfirmToken::create(new \DateInterval('P1D')));

        $manager->persist($user);
        $manager->flush();
    }
}
