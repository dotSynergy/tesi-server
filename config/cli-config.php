<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

// replace with file to your own project bootstrap
require_once __DIR__.'/../bootstrap.php';

// Setup Doctrine
$paths = array($_ENV['APP_ROOT'].'/src/App/Models');
$isDevMode = true;

$config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode,null, null, false);
$config->addEntityNamespace('', 'App\Models');

// Setup connection parameters
$dbParams = array(
    'driver' => 'pdo_pgsql',
    'user' => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASSWORD'],
    'dbname' => $_ENV['DB_NAME'],
    'host' => $_ENV['DB_HOST'],
    'charset' => 'utf8'
);

$entityManager = EntityManager::create($dbParams, $config);

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entityManager);
