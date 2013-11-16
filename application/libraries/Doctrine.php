<?php

class Doctrine
{
    // the Doctrine entity manager
    public $em = null;

    public function __construct()
    {
        // include our CodeIgniter application's database configuration
        require_once APPPATH.'config/database.php';

        // include Doctrine's fancy ClassLoader class
        require_once APPPATH.'libraries/Doctrine/Common/ClassLoader.php';

        // load the Doctrine classes
        $doctrineClassLoader = new \Doctrine\Common\ClassLoader('Doctrine', APPPATH.'libraries');
        $doctrineClassLoader->register();

        // load Symfony2 helpers
        // Don't be alarmed, this is necessary for YAML mapping files
        $symfonyClassLoader = new \Doctrine\Common\ClassLoader('Symfony', APPPATH.'libraries/Doctrine');
        $symfonyClassLoader->register();

        // load the entities
        $entityClassLoader = new \Doctrine\Common\ClassLoader('Entity', APPPATH.'models');
        $entityClassLoader->register();

        $migrationClassLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL\Migrations', APPPATH.'/libraries/Doctrine/DBAL/Migrations');
        $migrationClassLoader->register();		

        // load the proxy entities
        $proxyClassLoader = new \Doctrine\Common\ClassLoader('Proxies', APPPATH.'models');
        $proxyClassLoader->register();

        // set up the configuration 
        $config = new \Doctrine\ORM\Configuration;

        if(ENVIRONMENT == 'development')
            // set up simple array caching for development mode
            $cache = new \Doctrine\Common\Cache\ArrayCache;
        else
            // set up caching with APC for production mode
            $cache = new \Doctrine\Common\Cache\ApcCache;
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        // set up proxy configuration
        $config->setProxyDir(APPPATH.'models/Proxy');
        $config->setProxyNamespace('Proxy');

        // auto-generate proxy classes if we are in development mode
        $config->setAutoGenerateProxyClasses(ENVIRONMENT == 'development');

		// Create a simple "default" Doctrine ORM configuration for Annotations
        //Leo: uncomment following two lines if you want use php doc to create database.
		// $paths = APPPATH.'models/Entity';                  
		// $config->setMetadataDriverImpl($config->newDefaultAnnotationDriver($paths, true));		

        // set up annotation driver     Leo:  If you want to use Yaml to create database uncomment following two lines
        $yamlDriver = new \Doctrine\ORM\Mapping\Driver\YamlDriver(APPPATH.'models/Mappings');
        $config->setMetadataDriverImpl($yamlDriver);

        // Database connection information
        $connectionOptions = array(
            'driver' => 'pdo_mysql',
            'user' => $db['default']['username'],
            'password' => $db['default']['password'],
            'host' => $db['default']['hostname'],
            'dbname' => $db['default']['database']
        );
		// DEBUG :: WAYLAN
		//print_r($connectionOptions);
        // create the EntityManager
        $em = \Doctrine\ORM\EntityManager::create($connectionOptions, $config);
		//var_dump($em);
		//var_dump($em->getRepository('Entity\User'));
		//$testarray = $em->getRepository('Entity\User')->findAll();
		//var_dump($testarray);
        // store it as a member, for use in our CodeIgniter controllers.
        $this->em = $em;
    }
}