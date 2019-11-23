<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\InputFilter;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use DoctrineModule\Validator\ObjectExists;
use Zend\Filter;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

/**
 * Class ConfirmInputFilter
 * @package App\InputFilter
 */
class ConfirmInputFilter extends InputFilter implements ObjectManagerAwareInterface
{
    use ProvidesObjectManager;

    /**
     * ConfirmInputFilter constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->setObjectManager($objectManager);
        $this->init();
    }

    /**
     * @return ConfirmInputFilter
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
                    'name' => ObjectExists::class,
                    'options' => [
                        'object_repository' => $this
                            ->getObjectManager()
                            ->getRepository(User::class),
                        'fields' => ['email'],
                    ]
                ],
            ],
        ])->add([
            'name' => 'token',
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
                ],
            ],
        ]);

        return $this;
    }
}
