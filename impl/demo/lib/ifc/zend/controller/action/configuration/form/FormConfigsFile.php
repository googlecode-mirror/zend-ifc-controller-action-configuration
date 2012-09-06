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

namespace ifc\zend\controller\action\configuration\form;

require_once 'ifc/util/zend/Config.php';

/**
 * Form
 */
class FormConfigsFile extends \ifc\zend\controller\action\configuration\ZendAbstract {

    /**
     * string The key used to store this object into global Zend Registry
     */
    protected $_regKey = 'FormsConfigFile';
    /**
     * array of Zend_Form objects
     */
    protected $_forms = array();

    /**
     * Strategy pattern: Initialize the configuration.
     *
     * Create the new Zend_Form objects from the specified configuration files
     * for adding to this object and put this object into the Zend_Registry
     *
     * See {@link \Zend_Form}
     * See {@link \Zend_Registry}
     *
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function init(\Zend_Controller_Request_Abstract $request) {

        foreach ($this->_options as $id => $params) {
            if (is_array($params)) {
                // Check if the path parameter has been defined
                if (!isset($params['path'])) {
                    throw new Exception('The \'path\' parameter is required with
                        the path to form configuration file');
                }

                // Check if the class parameter has been defined
                if (isset($params['class'])) {
                    $formClass = $params['class'];
                } else {
                    //Use the defautl class (base class)
                    $formClass = '\Zend_Form';
                }

                if (!isset($params['section'])) {
                    $params['section'] = null;
                }

                //Get full path to configuration file
                $basePath = (!isset($params['inModule']) || ($params['inModule'] == true)) ?
                        \Zend_Controller_Front::getInstance()->getModuleDirectory() :
                        APPLICATION_PATH;

                $params['path'] = \ifc\util\zend\Config::prepareAbsolutePath($basePath, $params['path']);


                //Create form from configuration file
                $this->_forms[$id] = new $formClass(
                                \ifc\util\zend\Config::loadConfigFile($params['path'], $params['section']));
            } else {
                throw new Exception('The form configuration file specification
                    has to be an array');
            }
        }

        //Save it for later retrieval
        \Zend_Registry::set($this->_regKey, $this);
    }

    /**
     * Get the form associated with the specified identifier
     *
     * @param string $id The identifier of the form to get
     * @return {@link \Zend_Form} | null If exist returns the Zend_Form object
     *      otherwise returns null
     */
    public function getForm($id) {
        if (isset($this->_forms[$id])) {
            return $this->_forms[$id];
        } else {
            return null;
        }
    }

    /**
     * Get all cretaed {@link Zend_Form} objects
     *
     * @return array Key array with the existent Zend_Form objects. The keys
     *      are the identifier of each form.
     */
    public function getForms() {
        return $this->_forms;
    }

}

