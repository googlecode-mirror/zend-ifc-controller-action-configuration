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

namespace ifc\zend\module;

/**
 * Description of Configuration
 */
abstract class Configuration {

    // Module Configuration Parameters Key
    protected $_moduleConfigParamsKey = '';
    protected static $_instance;
    protected $_moduleConfigParams;


    /**
     * Return the module configuration paramerters object associated to the module.
     * The configuration object has to registred by the key value defined by
     * the constant: MODULE_CONFIGIGURATION_PARAMETERS_KEY, defined in this class
     *
     * @return ifc\zend\controller\action\configuration\ModuleConfigParameters
     *  The configuration object that store the module configuration parameters
     *
     * @throws ifc\zend\module\Exception If the module configuration has
     * not been defined into the configuraton file with the correct key
     */
    public function getModuleConfigParameters() {
        if (!isset($this->_moduleConfigParams)) {
            if (\Zend_Registry::isRegistered(
                            $this->_moduleConfigParamsKey)) {
                $this->_moduleConfigParams = \Zend_Registry::get(
                                $this->_moduleConfigParamsKey);
            } else {
                throw new Exception('The module configuration has not been defined
                    in the configuration file. You need to define the paramerer
                    ifc\zend\controller\action\configuration\ModuleConfigParameters.registryKey');
            }
        }

        return $this->_moduleConfigParams;
    }

    /**
     * Get the configured parameter value reference by the key in the indicated
     * level (multidimensional array)
     *
     * @param string $key The key to reference the value
     * @param string|array $hierarchy The key or keys into configuration parameters
     *   where the value is referenced
     * @return $string The parameter value
     *
     * @throws ifc\zend\module\Exception If the hierarchy parameter is not an
     *  accepted type
     */
    public function getParameter($key, $hierarchy = null) {
        if (!isset($hierarchy)) {
            $parameters = $this->getModuleConfigParameters()->getParameters();
            $parameter = $parameters[$key];
        } else {
            // When the parameter to get is in second dimension
            if (is_string($hierarchy)) {
                $parameters = $this->getModuleConfigParameters()->getParameters();;
                $parameter = $parameters[$hierarchy][$key];
            } else if (is_array($hierarchy)) {
                $parameters = $this->getModuleConfigParameters()->getParameters();;

                foreach ($hierarchy as $levelKey) {
                    $parameters = $parameters[$levelKey];
                }

                $parameter = $parameters[$key];
            } else {
                throw new Exception('Incorrect parameter type. Hierarchy parameter
                    has been strig or array');
            }
        }

        return $parameter;
    }


    /**
     * Get the key value used to register a concrete configuration
     *
     * @param string $key The key to reference the parameter that contains the
     *  used key to reference the configuration
     * @param string|array $hierarchy The key or keys where the parameter is
     *  located in configuration parameters hierarchy
     * @return \ifc\zend\controller\action\configuration\ConfigurationInterface
     *  The concrete configuration registered
     */
    public function getRegistredConfiguration($key, $hierarchy = null) {
        $registryKey = $this->getParameter($key, $hierarchy);

        if (\Zend_Registry::isRegistered($registryKey)) {
            return \Zend_Registry::get($registryKey);
        } else {
            return null;
        }
    }

}

