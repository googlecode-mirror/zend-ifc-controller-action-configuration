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

namespace ifc\zend\controller\action\configuration;

require_once 'ifc/zend/controller/action/configuration/ConfigurationAbstract.php';

/**
 * ZendAbstract
 */
abstract class ZendAbstract extends ConfigurationAbstract {

    /**
     * @var \Zend_Application_Bootstrap_BootstrapAbstract The instance of the
     *       bootstrap registred in the application
     */
    private static $_bootstrap = null;


    protected function _getBootstrap() {

        // return the boostrap instance if previously has assigned
        if (isset(ZendAbstract::$_bootstrap)) {
            return ZendAbstract::$_bootstrap;
        }

        // Get the bootstrap instance
        if (class_exists('Zend_Controller_Front')) {
            ZendAbstract::$_bootstrap = \Zend_Controller_Front::getInstance()->getParam('bootstrap');

            if (ZendAbstract::$_bootstrap === null) {
                require_once 'Exception.php';
                throw new Exception(
                        'Bootstarp is requiered, instance not found');
            }
        } else {
            require_once 'Exception.php';
            throw new Exception(
                    'Front controller class has not been loaded');
        }

        return ZendAbstract::$_bootstrap;
    }


    /**
     * Put the key value used to register the object into the global registry
     * if the subclass is prepared to store the instance into registry.
     *
     * The method check the existence of the attribute _regKey
     *
     * @param string $regKey
     */
    public function setRegistryKey($regKey) {

        if (property_exists($this, '_regKey')) {
            $this->_regKey = $regKey;
            unset($this->_options['registryKey']);
        }
    }

}

