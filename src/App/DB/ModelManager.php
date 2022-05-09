<?php

namespace App\DB;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;


class ModelManager {
    // Hold the class instance.
    private static $instance = null;
    private EntityManager $em;

    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {

        // Setup Doctrine
        $paths = array(__DIR__.'/../../../src/App/Models');
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

        $this->em = EntityManager::create($dbParams, $config);
    }
    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance()
    {
        if (self::$instance == null)
            self::$instance = new ModelManager();

        return self::$instance->em;
    }
}