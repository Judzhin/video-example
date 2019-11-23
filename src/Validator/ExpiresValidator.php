<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Validator;

use Zend\Validator\AbstractValidator;

/**
 * Class ExpiresValidator
 * @package App\Validator
 */
class ExpiresValidator extends AbstractValidator
{
    /** @var string */
    const INVALID_VALUE = 'invalidValue';

    /** @var array */
    protected $messageTemplates = [
        self::INVALID_VALUE => "Date is expired",
    ];

    /** @var \DateTimeImmutable|null */
    protected $date = null;

    /**
     * @param \DateTimeImmutable|null $date
     * @return ExpiresValidator
     */
    public function setDate(?\DateTimeImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @inheritdoc
     *
     * @param mixed $value
     * @return bool
     */
    public function isValid($value): bool
    {
        if ($this->date <= $value) {
            $this->error(self::INVALID_VALUE);
            return false;
        }

        return true;
    }
}
