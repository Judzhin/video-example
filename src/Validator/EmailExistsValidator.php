<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Validator;

use DoctrineModule\Validator\NoObjectExists;

/**
 * Class EmailExistsValidator
 * @package App\Validator
 */
class EmailExistsValidator extends NoObjectExists
{
    /**
     * @var array Message templates
     */
    protected $messageTemplates = [
        self::ERROR_OBJECT_FOUND => "User with this email '%value%' already exists",
    ];
}
