<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Entity;

use App\Entity\ConfirmToken;
use PHPUnit\Framework\TestCase;

/**
 * Class ConfirmTokenTest
 * @package AppTest\Entity
 */
class ConfirmTokenTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testInstance()
    {
        /** @var ConfirmToken $instance */
        $instance = ConfirmToken::create();
        $this->assertInstanceOf(ConfirmToken::class, $instance);
        $this->assertNotSame(ConfirmToken::create(), $instance);
    }

    /**
     * @throws \Exception
     */
    public function testSuccess(): void
    {
        /** @var ConfirmToken $confirmToken */
        $confirmToken = ConfirmToken::create();
        $confirmToken->validate(
            $confirmToken->getValue(),
            $confirmToken->getExpires()->modify('-1 day')
        );

        self::assertNull($confirmToken->getValue());
        self::assertNull($confirmToken->getExpires());
    }

    /**
     * @throws \Exception
     * @expectedException \App\Exception\ValidationException
     */
    public function testInvalidToken(): void
    {
        /** @var ConfirmToken $confirmToken */
        $confirmToken = ConfirmToken::create();
        $confirmToken->validate(
            'invalid', $confirmToken->getExpires()->modify('-1 day')
        );
        $this->expectExceptionMessage('Validation errors');
    }

    /**
     * @throws \Exception
     * @expectedException \App\Exception\ValidationException
     */
    public function testExpiredToken(): void
    {
        /** @var ConfirmToken $confirmToken */
        $confirmToken = ConfirmToken::create();
        $confirmToken->validate(
            $confirmToken->getValue(), (new \DateTimeImmutable)->modify('+4 day')
        );
        $this->expectExceptionMessage('Validation errors');
    }
}