<?php

$config = new Doctrine\ORM\Configuration(); 

// Proxy Configuration

$config->setProxyDir(APPLICATION_PATH .'../data/doctrine/proxies');
$config->setProxyNamespace('bttranking\model\proxies');
$config->setAutoGenerateProxyClasses((APPLICATION_ENV == "development"));
//$config->setAutoGenerateProxyClasses(false);



// Mapping Configuration (4)
//$driverImpl = new Doctrine\ORM\Mapping\Driver\XmlDriver(__DIR__."/config/mappings/xml");
//$driverImpl = new Doctrine\ORM\Mapping\Driver\YamlDriver(__DIR__."/config/mappings/yml");
$driverImpl = $config->newDefaultAnnotationDriver(APPLICATION_PATH . '../data/doctrine/entities');
$config->setMetadataDriverImpl($driverImpl);


// Caching Configuration (5)
if (APPLICATION_ENV == "development") {
    $cache = new \Doctrine\Common\Cache\ArrayCache();
} else {
    $cache = new \Doctrine\Common\Cache\ApcCache();
}
$config->setMetadataCacheImpl($cache);
$config->setQueryCacheImpl($cache);

// database configuration parameters (6)
$connectionOptions = array(
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'dbname'=> 'bttranking_main',
    'user' => 'biker',
    'password' => 'biker'
);


// obtaining the entity manager (7)
$evm = new Doctrine\Common\EventManager();
$entityManager = \Doctrine\ORM\EntityManager::create($connectionOptions, $config, $evm);

/**/