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

/**
 * ModuleConfigParameters
 * This configuration class is a simple supplier of parameters for configuring a
 * concrete module.
 * If this configuration is registred with the same key of other object of this
 * class in other scope that is executed in different time, then the paramerters
 * are merged using the array_merge_recursive PHP function.
 */
class ModuleConfigParameters extends \ifc\zend\controller\action\configuration\ZendAbstract {

    /**
     * string The key used to store this object into global Zend Registry
     */
    protected $_regKey = 'ModuleConfigParameters';

    /**
     * Strategy pattern: Initialize the configuration.
     *
     * Add parameters from configuraiton file and register into this object
     * and put this object into the Zend_Regitry
     *
     * If other object of this class exist into the Zend_Registry with the same
     * key used in this objet, then the parameters are merged using the
     * array_merge_recursive PHP function.
     *
     * See {@link \Zend_Registry}
     *
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function init(\Zend_Controller_Request_Abstract $request) {
        //Check ther registry contains the key that will be used to registry this object
        if (\Zend_Registry::isRegistered($this->_regKey)) {
            $prevModueConfigParams = \Zend_Registry::get($this->_regKey);

            //Check if other object is the same class of this, for merging options
            if (is_a($prevModueConfigParams, __CLASS__)) {
                $this->_options = array_merge_recursive($prevModueConfigParams->getParameters(), $this->_options);
            }
        }

        //Register the object
        \Zend_Registry::set($this->_regKey, $this);
    }


    /**
     * Get the parameter with a public attribute of the objet
     *
     * @see __get PHP magic method
     *
     * @param string $name Name of the parameter to get
     * @return @see getParameter method
     */
    public function __get($name) {
        return $this->getParameter($name);
    }

    /**
     * Get the parameter associated with the specified name
     *
     * @param string $name The name of the parameter to get
     * @return mixed | null If exist returns the value (the type depents of each
     *      parameter) of the parameter otherwise returns null
     */
    public function getParameter($name) {
        if (isset($this->_options[$name])) {
            return $this->_options[$name];
        } else {
            return null;
        }
    }

    /**
     * Get all registred parameters
     *
     * @return array Key array with the values of the existent parameters.
     *      The keys are the name of each parameter.
     */
    public function getParameters() {
        return $this->_options;
    }

    public function hasParameter($name) {
        return isset($this->_options[$name]) ? true : false;
    }

}
