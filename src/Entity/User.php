<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\EventManager\EventManagerAwareTrait;

/**
 * Class User
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="users", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"email"})
 * })
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @ORM\GeneratedValue("UUID")
     */
    private $id;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetime_immutable")
     */
    private $date;

    /**
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @ORM\Column(type="string", name="password")
     */
    private $password;

    /**
     * @var ConfirmToken
     * @ORM\Embedded(class="ConfirmToken", columnPrefix="confirm_token_")
     */
    private $confirmToken;

    /** @const STATUS_WAIT  */
    private const STATUS_WAIT = 'WAIT';

    /** @const STATUS_ACTIVE  */
    private const STATUS_ACTIVE = 'ACTIVE';

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $status = self::STATUS_WAIT;

    /**
     * User constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->setDate(new \DateTimeImmutable);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return User
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTimeImmutable $date
     * @return $this
     */
    public function setDate(\DateTimeImmutable $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return ConfirmToken
     */
    public function getConfirmToken(): ConfirmToken
    {
        return $this->confirmToken;
    }

    /**
     * @param ConfirmToken $confirmToken
     * @return User
     */
    public function setConfirmToken(ConfirmToken $confirmToken): self
    {
        $this->confirmToken = $confirmToken;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return self::STATUS_ACTIVE == $this->status;
    }

    /**
     * @ORM\PostLoad()
     * @return User
     */
    public function onPostLoad(): self
    {
        if ($this->confirmToken->isEmpty()) {
            $this->confirmToken = null;
        }

        return $this;
    }

    /**
     * @param string $token
     * @param \DateTimeImmutable|null $expires
     * @return User
     * @throws \Exception
     */
    public function activate(string $token, \DateTimeImmutable $expires = null): self
    {
        $this->getConfirmToken()->validate($token, $expires);
        $this->status = self::STATUS_ACTIVE;

        return $this;
    }
}
