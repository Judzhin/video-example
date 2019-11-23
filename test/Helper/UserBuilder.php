<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace AppTest\Helper;

use App\Entity\ConfirmToken;
use App\Entity\User;
use MSBios\Exception\LogicException;
use Ramsey\Uuid\Uuid;

/**
 * Class UserBuilder
 * @package AppTest\Helper
 *
 * @method withId($id)
 * @method withDate(\DateTimeImmutable $date)
 * @method withEmail($email)
 * @method withPassword($password)
 * @method withConfirmToken(ConfirmToken $confirmToken)
 */
class UserBuilder
{
    /** @var string */
    private $id;

    /** @var \DateTimeImmutable */
    private $date;

    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /** @var ConfirmToken */
    private $confirmToken;

    /**
     * UserBuilder constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->date = new \DateTimeImmutable;
        $this->email = 'demo@example.com';
        $this->password = 'password';
        $this->confirmToken = ConfirmToken::create(new \DateInterval("P3D"));
    }

    /**
     * @param $name
     * @param $arguments
     * @return UserBuilder
     */
    public function __call($name, $arguments): self
    {
        if (0 === strpos($name, 'with')) {

            /** @var self $clone */
            $clone = clone $this;
            $clone->{lcfirst(substr($name, 4))} = $arguments[0];
            return $clone;
        }

        throw new LogicException('WTF Exception with call method!!!');
    }

    /**
     * @return User
     * @throws \Exception
     */
    public function build(): User
    {
        return (new User)
            ->setId($this->id)
            ->setDate($this->date)
            ->setEmail($this->email)
            ->setPassword($this->password)
            ->setConfirmToken($this->confirmToken);
    }

}