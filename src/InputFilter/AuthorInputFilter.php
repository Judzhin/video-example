<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\InputFilter;

use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use DoctrineModule\Persistence\ProvidesObjectManager;
use DoctrineModule\Validator\ObjectExists;
use Ramsey\Uuid\Uuid;
use Zend\Filter;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

/**
 * Class AuthorInputFilter
 * @package App\InputFilter
 */
class AuthorInputFilter extends InputFilter implements ObjectManagerAwareInterface
{
    use ProvidesObjectManager;

    /**
     * AuthorInputFilter constructor.
     * @param ObjectManager $objectManager
     */
    public function __construct(ObjectManager $objectManager)
    {
        $this->setObjectManager($objectManager);
        $this->init();
    }

    /**
     * @return AuthorInputFilter
     */
    public function init(): self
    {
        parent::init();

        $this->add([
            'name' => 'id',
            'required' => true,
            'validators' => [
                [
                    'name' => Validator\NotEmpty::class,
                ], [
                    'name' => Validator\IsInstanceOf::class,
                    'options' => [
                        'className' => Uuid::class
                    ]
                ], [
                    'name' => ObjectExists::class,
                    'options' => [
                        'object_repository' => $this
                            ->getObjectManager()
                            ->getRepository(User::class),
                        'fields' => ['id'],
                    ]
                ],
            ],
        ])->add([
            'name' => 'name',
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
