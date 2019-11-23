<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

use Symfony\Component\Dotenv\Dotenv;
use Zend\ServiceManager\ServiceManager;

chdir(dirname(__DIR__));

/** @var \Composer\Autoload\ClassLoader $loader */
$loader = require 'vendor/autoload.php';

// TODO: Need to fine another method for connect Doctrine
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');

// If the Dotenv class exists, load env vars and enable errors
if (class_exists(Dotenv::class)) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    (new Dotenv)->load(__DIR__ . '/../.env');
}

// Load configuration
$config = require __DIR__ . '/config.php';

$dependencies = $config['dependencies'];
$dependencies['services']['config'] = $config;

// Build container
return new ServiceManager($dependencies);
