<?php
/**
 * IFC CONTROLLER ACTION CONFIGURATION & MODULES (ZEND FRAMEWORK)
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 *
 * @copyright  Copyright (c) 2012 Ivan Fraixedes Cugat
 * @license    New BSD License
 * @author     Ivan Fraixedes Cugat
 */

namespace ifc\zend\controller\action\configuration\database;

require_once 'ifc/util/zend/Config.php';

/**
 * DoctrineConfiguration
 */
class DoctrineConfiguration extends \ifc\zend\controller\action\configuration\ZendAbstract {

    /**
     * @var string The key used to store this object into global Zend Registry
     */
    protected $_regKey = 'Doctrine';
    /**
     * @var array The options of the data base connection used in doctrine EntityManager
     *
     * @see {@link \Doctrine\ORM\EntityManager}
     */
    protected $_dbConnectionOptions;
    /**
     * @var {@link \Doctrine\ORM\Configuration}
     *
     * Configuration instance used to create the EntityManager object
     */
    protected $_ormConfig;
    /**
     * @var {@link \Doctrine\ORM\EntityManager}
     *
     * EntityManager instance created with the specified parametners in doctrine
     *  configuration file
     */
    protected $_entityManager;

    /**
     * Strategy pattern: Initialize the configuration.
     *
     * Change the head link elements of the web page according the configuration
     * pararmeters
     *
     *
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function init(\Zend_Controller_Request_Abstract $request) {

        if (!isset($this->_options['path'])) {
            throw new Exception('The \'path\' parameter is required with
                        the path to Doctrine ORM configuration file');
        }

        $fconfigSection = (!isset($this->_options['section'])) ? null : $this->_options['section'];

        //Get full path to configuration file
        $basePath = (!isset($params['inModule']) || ($params['inModule'] == true)) ?
                \Zend_Controller_Front::getInstance()->getModuleDirectory() :
                APPLICATION_PATH;

        $this->_options['path'] = \ifc\util\zend\Config::prepareAbsolutePath($basePath, $this->_options['path']);

        //Create and configure Doctrine ORM
        $this->configure(\ifc\util\zend\Config::loadConfigFile($this->_options['path'], $this->_options['section'])->toArray());

        //Save it for later retrieval
        \Zend_Registry::set($this->_regKey, $this);
    }

    /**
     * @todo document this method
     * @param array $config
     */
    public function configure(array $config) {

        if (!isset($config['connection']) || !is_array($config['connection'])) {
            throw new Exception('The \'connection\' configuration parameter is
                    required and this has to be an array');
        }

        $this->_dbConnectionOptions = $config['connection'];
        $this->_ormConfig = new \Doctrine\ORM\Configuration();

        //Create ORM Mapping driver to use
        if (isset($config['mappingDriver'])) {
            $driverMappingConfig = $config['mappingDriver'];

            if (!isset($driverMappingConfig['type']) || !isset($driverMappingConfig['mappingDocsPath'])) {
                throw new Exception('\'type\' and \'mappingDocsPath\' parameters are
                    required by mapping driver configuration');
            }

            //Get full path to mapping documents
            if (is_array($driverMappingConfig['mappingDocsPath'])) {
                foreach ($driverMappingConfig['mappingDocsPath'] as &$path) {
                    $path = \ifc\util\zend\Config::prepareAbsolutePath(
                                    APPLICATION_PATH, $path);
                }
            } else {
                $driverMappingConfig['mappingDocsPath'] =
                        \ifc\util\zend\Config::prepareAbsolutePath(
                                APPLICATION_PATH, $driverMappingConfig['mappingDocsPath']);
            }

            $driverMappingConfig['type'] = strtolower($driverMappingConfig['type']);

            switch ($driverMappingConfig['type']) {
                case 'xml':
                    $driverImpl = new Doctrine\ORM\Mapping\Driver\XmlDriver(
                                    $driverMappingConfig['mappingDocsPath']);
                    break;
                case 'yml':
                    $driverImpl = new Doctrine\ORM\Mapping\Driver\YamlDriver(
                                    $driverMappingConfig['mappingDocsPath']);
                    break;
                case 'annotation':
                    $driverImpl = $this->_ormConfig->newDefaultAnnotationDriver(
                                    $driverMappingConfig['mappingDocsPath']);
                    break;
                case 'php':
                    $driverImpl = new Doctrine\ORM\Mapping\Driver\PHPDriver(
                                    $driverMappingConfig['mappingDocsPath']);
                    break;
                default:
                    throw new Exception('Unrecognized ORM driver mapping');
            }

            $this->_ormConfig->setMetadataDriverImpl($driverImpl);
        } else {
            throw new Exception('The \'mappingDriver\' configuration parameter is required');
        }

        //Parametrize configuration object
        if (isset($config['configuration'])) {
            //Each defined parameters of the {@link \Doctrine\ORM\Configuration} is
            //the name of one setters methods of the  that only receive one parameter
            // of a simple type
            /*
              foreach ($config['configuration'] as $param => $value) {
              $method = 'set' . $param;

              if (method_exists($this->_ormConfig, $method)) {
              $this->_ormConfig->$method($value);
              } else {
              throw new Exception($param . ' is is not a correct parameter
              for ORM Configuration; the accepted parameters to configure the
              \\Doctrine\\ORM\\Configuration are the name of the setter methods
              without the \'set\' prefix that accept one parameter of a basic type.');
              }
              }/* */
            $configParams = $config['configuration'];

            // If exist ProxyDir parameter, set the Proxy directori
            if (isset($configParams['proxyDir'])) {
                $this->_ormConfig->setProxyDir(
                        $path = \ifc\util\zend\Config::prepareAbsolutePath(
                                APPLICATION_PATH, $configParams['proxyDir']));
            }

            // If exist ProxyNamespace parameter, set the Proxy namespace
            if (isset($configParams['proxyNamespace'])) {
                $this->_ormConfig->setProxyNamespace($configParams['proxyNamespace']);
            }

            // If exist AutoGenerateProxyClasses parameter, set the flag if
            // the proxy classess have to be autogenerated
            if (isset($configParams['autoGenerateProxyClasses'])) {
                $this->_ormConfig->setAutoGenerateProxyClasses(
                        $path = \ifc\util\zend\Config::prepareAbsolutePath(
                                APPLICATION_PATH, $configParams['autoGenerateProxyClasses']));
            }
        }

        //Configure cache
        if (isset($config['cache'])) {
            $cacheConfig = $config['cache'];

            if (!isset($cacheConfig['class'])) {
                throw new Exception('The \'class\' configuration parameter is required
                    to configure ORM cache');
            }

            if (!class_exists($cacheConfig['class'])) {
                throw new Exception($cacheConfig['class'] . ' not found');
            }

            $cache = new $cacheConfig['class']();

            if (isset($cacheConfig['uses'])) {
                /*
                  foreach ($cacheConfig['uses'] as $use => $flag) {
                  if ($flag) {
                  $method = 'set' . $use;

                  if (method_exists($this->_ormConfig, $method)) {
                  $this->_ormConfig->$method($cache);
                  } else {
                  throw new Exception($use . ' is is not a correct cache use
                  for ORM Configuration; the accepted parameters to configure the
                  cache of \\Doctrine\\ORM\\Configuration are the name of the
                  setter methods without the \'set\' prefix that accept one parameter
                  of a class that implements \\Doctrine\\Common\\Cache interface.');
                  }
                  }
                  }/* */

                $cacheUseConfig = $cacheConfig['uses'];

                //Puts the Metadata cache if this is required
                if (isset($cacheUseConfig['metadata']) && ($cacheUseConfig['metadata'])) {
                    $this->_ormConfig->setMetadataCacheImpl($cache);
                }

                //Puts the Query cache if this is required
                if (isset($cacheUseConfig['query']) && ($cacheUseConfig['query'])) {
                    $this->_ormConfig->setQueryCacheImpl($cache);
                }

                //Puts the Result cache if this is required
                if (isset($cacheUseConfig['result']) && ($cacheUseConfig['result'])) {
                    $this->_ormConfig->setResultCacheImpl($cache);
                }
            }
        }

        $this->_entityManager = \Doctrine\ORM\EntityManager::create(
                        $this->_dbConnectionOptions, $this->_ormConfig, new \Doctrine\Common\EventManager());
    }

    public function getDbConnectionOptions() {
        return $this->_dbConnectionOptions;
    }

    public function getConfiguration() {
        return $this->_ormConfig;
    }

    public function getEntityManager() {
        return $this->_entityManager;
    }

}
