<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Entity;

use App\Entity\ConfirmToken;
use App\Entity\User;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * Class UserTest
 * @package AppTest\Entity
 */
class UserTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        /** @var User $user */
        $user = (new User)
            ->setId($id = Uuid::uuid4()->toString())
            ->setDate($date = new \DateTimeImmutable)
            ->setEmail($email = 'mail@example.com')
            ->setPassword($password = 'password')
            ->setConfirmToken($confirmToken = ConfirmToken::create());

        self::assertFalse($user->isActive());
        self::assertEquals($id, $user->getId());
        self::assertEquals($date, $user->getDate());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($password, $user->getPassword());
        self::assertEquals($confirmToken, $user->getConfirmToken());
        self::assertInstanceOf(
            User::class,
            $user->activate($confirmToken->getValue(), $confirmToken->getExpires()->modify('-1 day'))
        );
        self::assertInstanceOf(User::class, $user->onPostLoad());
    }
}