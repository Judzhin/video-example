<?php

declare(strict_types=1);

return [
    // Provides application-wide services.
    // We recommend using fully-qualified class names whenever possible as
    // service names.
    'dependencies' => [
        'abstract_factories' => [
            // \Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory::class
        ],
        // Use 'aliases' to alias a service name to another service. The
        // key is the alias name, the value is the service to which it points.
        'aliases' => [
            // Doctrine
            \Doctrine\Common\Persistence\ObjectManager::class =>
                'doctrine.entity_manager.orm_default',
            \Doctrine\ORM\EntityManagerInterface::class =>
                \Doctrine\Common\Persistence\ObjectManager::class,
            \Doctrine\ORM\EntityManager::class =>
                \Doctrine\ORM\EntityManagerInterface::class,
            \Zend\Expressive\Authentication\AuthenticationInterface::class =>
                \Zend\Expressive\Authentication\OAuth2\OAuth2Adapter::class
        ],
        // Use 'invokables' for constructor-less services, or services that do
        // not require arguments to the constructor. Map a service name to the
        // class name.
        'invokables' => [
            // Fully\Qualified\InterfaceName::class => Fully\Qualified\ClassName::class,
        ],
        // Use 'factories' for services provided by callbacks/factory classes.
        'factories' => [
            // Doctrine
            'doctrine.entity_manager.orm_default' =>
                \ContainerInteropDoctrine\EntityManagerFactory::class,
            \Symfony\Component\Validator\Validator\ValidatorInterface::class => function () {
                return \Symfony\Component\Validator\Validation::createValidatorBuilder()
                    ->enableAnnotationMapping()
                    ->getValidator();
            },
        ],
    ],
];
