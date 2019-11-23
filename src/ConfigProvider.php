<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use MSBios\Doctrine\Factory\ObjectableFactory;

/**
 * Class ConfigProvider
 * @package App
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'doctrine' => $this->getDoctrine(),
            'cli' => $this->getConsole(),
            'templates' => $this->getTemplates(),
            'events' => $this->getEvents(),
            'kafka' => $this->getKafka(),
            __CLASS__ => $this->getPackageConfig()
        ];
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                // ...
            ],
            'factories' => [

                // Command
                Command\Amqp\ConsumerCommand::class =>
                    Command\Amqp\ConnectionCommandFactory::class,
                Command\Amqp\ProducerCommand::class =>
                    Command\Amqp\ConnectionCommandFactory::class,
                Command\Kafka\ConsumerCommand::class =>
                    Command\Kafka\ConsumerCommandFactory::class,
                Command\Kafka\ProducerCommand::class =>
                    Command\Kafka\ProducerCommandFactory::class,
                Command\FixtureCommand::class =>
                    \MSBios\Doctrine\Factory\ObjectableFactory::class,
                Command\GreetCommand::class =>
                    Command\GreetCommandFactory::class,

                // EventListener
                Listener\Author\VideoListener::class =>
                    Listener\Author\VideoListenerFactory::class,
                Listener\ConfirmListener::class =>
                    Listener\SmtTransportableListenerFactory::class,
                Listener\SignUpListener::class =>
                    Listener\SmtTransportableListenerFactory::class,

                // EventManager
                \Zend\EventManager\EventManager::class =>
                    EventManagerFactory::class,

                // Handler
                Handler\Author\VideoHandler::class =>
                    Handler\Author\VideoHandlerFactory::class,
                Handler\AuthorHandler::class =>
                    ObjectableFactory::class,
                Handler\ConfirmHandler::class =>
                    Handler\ConfirmHandlerFactory::class,
                Handler\HomePageHandler::class =>
                    Handler\HomePageHandlerFactory::class,
                Handler\ProfileHandler::class =>
                    ObjectableFactory::class,
                Handler\SignUpHandler::class =>
                    Handler\SignUpHandlerFactory::class,

                // Cli Application
                \Symfony\Component\Console\Application::class =>
                    ApplicationFactory::class,

                // Mail
                \Zend\Mail\Transport\Smtp::class =>
                    SmtpTransportFactory::class,

                // Services
                // \Kafka\Consumer::class =>
                //     Service\Kafka\ConsumerFactory::class,
                // \Kafka\Producer::class =>
                //     Service\Kafka\ProducerFactory::class,

                MessageComponent::class =>
                    MessageComponentFactory::class,

                // Services
                Service\Video\Converter\Converter::class =>
                    Service\Video\Converter\ConverterFactory::class,
                Service\Video\FormatDetector\FFProbeFormatDetector::class =>
                    Service\Video\FormatDetector\FFProbeFormatDetectorFactory::class,
                Service\Video\Thumbnailer\Thumbnailer::class =>
                    Service\Video\Thumbnailer\ThumbnailerFactory::class,
                Service\Video\Preference::class =>
                    Service\Video\PreferenceFactory::class,

                \League\OAuth2\Server\CryptKey::class =>
                    CryptKeyFactory::class,

                \Ratchet\Server\IoServer::class =>
                    IoServerFactory::class,
            ],
        ];
    }

    /**
     * Returns doctrine configuration
     *
     * @return array
     */
    public function getDoctrine(): array
    {
        return [
            'cache_dir' => './data/Doctrine/Proxy',
            'driver' => [
                'orm_default' => [
                    'class' => MappingDriverChain::class,
                    'drivers' => [
                        'App\Entity' =>
                            __NAMESPACE__,
                        'Zend\Expressive\Authentication\OAuth2\Entity' =>
                            \Zend\Expressive\Authentication\ConfigProvider::class,
                    ],
                ],
                __NAMESPACE__ => [
                    'class' => AnnotationDriver::class,
                    'cache' => 'array',
                    'paths' => __DIR__ . '/Entity',
                ],
                \Zend\Expressive\Authentication\ConfigProvider::class => [
                    'class' => XmlDriver::class,
                    'paths' => __DIR__ . '/Entity',
                ],
            ],
        ];
    }

    /**
     * Returns the console configuration
     *
     * @return array
     */
    public function getConsole(): array
    {
        return [
            'name' => '',
            'version' => '',
            'commands' => [
                Command\Amqp\ConsumerCommand::NAME =>
                    Command\Amqp\ConsumerCommand::class,
                Command\Amqp\ProducerCommand::NAME =>
                    Command\Amqp\ProducerCommand::class,
                // Command\Kafka\ConsumerCommand::NAME =>
                //     Command\Kafka\ConsumerCommand::class,
                // Command\Kafka\ProducerCommand::NAME =>
                //     Command\Kafka\ProducerCommand::class,
                Command\FixtureCommand::NAME =>
                    Command\FixtureCommand::class,
                Command\GreetCommand::NAME =>
                    Command\GreetCommand::class,
            ]
        ];
    }

    /**
     * Returns the templates configuration
     *
     * @return array
     */
    public function getTemplates(): array
    {
        return [
            'paths' => [
                'app' => [
                    'templates/app'
                ],
                'error' => [
                    'templates/error'
                ],
                'layout' => [
                    'templates/layout'
                ],
            ],
        ];
    }

    /**
     * Returns events configuration
     * @return array
     */
    public function getEvents(): array
    {
        return [
            Handler\Author\VideoHandler::class => [
                [
                    'listener' => Listener\Author\VideoListener::class,
                    'method' => '__invoke',
                ]
            ],
            Handler\SignUpHandler::class => [
                [
                    'listener' => Listener\SignUpListener::class,
                    'method' => '__invoke',
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function getKafka(): array
    {
        return [
            'broker_list' => 'kafka:9092'
        ];
    }

    /**
     * @return array
     */
    public function getPackageConfig(): array
    {
        return [
            'storage' => 'http://0.0.0.0:1986/',
            'target' => __DIR__ . '/../data/upload',
            'thumbnail' => [854, 480],
            'videos' => [
                [640, 360],
                [854, 480],
                [1280, 720],
                [1920, 1080],
            ],
            'formats' => [
                'webm',
                'mp4',
            ],
            'thumbnailer_resolvers' => [
                Service\Video\Thumbnailer\FFMpegResolver::class => 100,
            ],
            'converter_resolvers' => [
                Service\Video\Converter\FFMpegMp4Resolver::class => 100,
                Service\Video\Converter\FFMpegWebmResolver::class => 100,
            ],
        ];
    }
}
