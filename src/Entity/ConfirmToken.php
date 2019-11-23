<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity;

use App\Exception\ValidationException;
use App\Validator\ExpiresValidator;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory;
use Zend\InputFilter\InputFilterInterface;
use Zend\Math\Rand;
use Zend\Stdlib\AbstractOptions;
use Zend\Validator;

/**
 * Class ConfirmToken
 * @package App\Entity
 *
 * @ORM\Embeddable
 */
class ConfirmToken extends AbstractOptions
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true, name="value")
     */
    private $value = null;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $expires = null;

    /**
     * @return null|string
     */
    public function getValue(): ?string
    {
        return $this->value;
    }

    /**
     * @param $value
     * @return ConfirmToken
     */
    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getExpires(): ?\DateTimeImmutable
    {
        return $this->expires;
    }

    /**
     * @param \DateTimeImmutable $expires
     * @return ConfirmToken
     */
    public function setExpires(\DateTimeImmutable $expires): self
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * @param \DateInterval|null $interval
     * @return ConfirmToken
     * @throws \Exception
     */
    public static function create(\DateInterval $interval = null): self
    {
        return new self([
            'value' => Rand::getString(10),
            'expires' => (new \DateTimeImmutable)->add($interval ?? new \DateInterval("P3D"))
        ]);
    }

    /**
     * @param $value
     * @param \DateTimeImmutable|null $expires
     * @return ConfirmToken
     * @throws \Exception
     */
    public function validate($value, \DateTimeImmutable $expires = null): self
    {
        /** @var InputFilterInterface $inputFilter */
        $inputFilter = (new Factory)->createInputFilter([
            'token' => [
                'name' => 'token',
                'required' => true,
                'validators' => [
                    [
                        'name' => Validator\NotEmpty::class,
                    ], [
                        'name' => Validator\Identical::class,
                        'options' => [
                            'token' => $this->value
                        ]
                    ],
                ],
            ],
            'expires' => [
                'name' => 'expires',
                'required' => true,
                'validators' => [
                    [
                        'name' => Validator\NotEmpty::class,
                    ], [
                        'name' => ExpiresValidator::class,
                        'options' => [
                            'date' => $this->expires,
                        ]
                    ],
                ],
            ]
        ])->setData([
            'token' => $value,
            'expires' => $expires ?? new \DateTimeImmutable
        ]);

        if (! $inputFilter->isValid()) {
            throw new ValidationException($inputFilter);
        }

        $this->value = null;
        $this->expires = null;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->value);
    }
}
