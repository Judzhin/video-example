<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App;

use Interop\Container\ContainerInterface;
use Zend\Mail;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class SmtpTransportFactory
 * @package App
 */
class SmtpTransportFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object|Mail\Transport\Smtp
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var array $config */
        $config = $container->get('config');

        return (new Mail\Transport\Smtp)->setOptions(
            new Mail\Transport\SmtpOptions($config['smtp_options'])
        );
    }
}
