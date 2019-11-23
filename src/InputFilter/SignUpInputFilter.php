<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\InputFilter;

use App\Entity\User;
use App\Validator\EmailExistsValidator;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use DoctrineModule\Validator\NoObjectExists;
use Zend\Filter;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

/**
 * Class SingUpInputFilter
 * @package App\InputFilter
 */
class SignUpInputFilter extends InputFilter implements ObjectManagerAwareInterface
{
    use ProvidesObjectManager;

    /**
     * SignUpInputFilter constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->setObjectManager($objectManager);
        $this->init();
    }

    /**
     * @return SignUpInputFilter
     */
    public function init(): self
    {
        parent::init();

        $this->add([
            'name' => 'email',
            'required' => true,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ], [
                    'name' => Filter\StripTags::class
                ],
            ],
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class,
                ], [
                    'name' => Validator\EmailAddress::class,
                ], [
                    // 'name' => NoObjectExists::class,
                    'name' => EmailExistsValidator::class,
                    'options' => [
                        'object_repository' => $this->getObjectManager()->getRepository(User::class),
                        'fields' => ['email'],
                    ]
                ],
            ],
        ])->add([
            'name' => 'password',
            'required' => true,
            'filters' => [
                [
                    'name' => Filter\StringTrim::class
                ], [
                    'name' => Filter\StripTags::class
                ],
            ],
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class,
                ], [
                    'name' => Validator\StringLength::class,
                    'options' => [
                        'min' => 8
                    ],
                ],
            ],
        ]);

        return $this;
    }
}
