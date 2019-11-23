<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Service\Video\FormatDetector;

use App\Service\Video\Preference;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Class FFProbeFormatDetectorFactory
 * @package App\Service\Video\FormatDetector
 */
class FFProbeFormatDetectorFactory implements FactoryInterface
{
    /**
     * @inheritdoc
     *
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return FFProbeFormatDetector|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Preference $preference */
        $preference = $container->get(Preference::class);
        return new FFProbeFormatDetector($preference->getTarget());
    }
}
