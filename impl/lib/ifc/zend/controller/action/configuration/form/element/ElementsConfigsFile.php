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

namespace ifc\zend\controller\action\configuration\form\element;

require_once 'ifc/util/zend/Config.php';

/**
 * ElementsConfigFile
 * @todo Revisar los comentarios de los atributos y comentar los metodos
 */
class ElementsConfigsFile extends \ifc\zend\controller\action\configuration\ZendAbstract {

    /**
     * string The key used to store this object into global Zend Registry
     */
    protected $_regKey = 'FormElementsConfigFile';
    /**
     * array Keyed array with subarrays of Zend_Config inherited objects or
     *      directly Zend_Config inherited objects for creating Zend_Form_Element
     *      that have been registred
     */
    protected $_elementConfigs = array();
    /**
     * array Keyed array with subarrays of Zend_Form_Element objects
     */
    protected $_cachedFormElements = array();

    /**
     * Strategy pattern: Initialize the configuration.
     *
     * Create and mantain the reference to the Zend_Form_Element or subclass objects
     * from the specified configuration files and put this object into the Zend_Registry
     *
     * See {@link \Zend_Form_Element}
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

                if (!isset($params['element'])) {
                    $params['element'] = null;
                }

                //Get full path to configuration file
                $basePath = (!isset($params['inModule']) || ($params['inModule'] == true)) ?
                        \Zend_Controller_Front::getInstance()->getModuleDirectory() :
                        APPLICATION_PATH;

                $params['path'] = \ifc\util\zend\Config::prepareAbsolutePath($basePath, $params['path']);


                //Create elements from configuration file
                $this->_elementConfigs[$id] =
                        \ifc\util\zend\Config::loadConfigFile($params['path'], $params['element']);
            } else {
                throw new Exception('The elements configuration file specification
                    has to be an array');
            }
        }

        //Save it for later retrieval
        \Zend_Registry::set($this->_regKey, $this);
    }

    protected function _prepareElementConfig($fullElementId) {

        if (isset($this->_cachedFormElements[$fullElementId])) {
            return $this->_cachedFormElements[$fullElementId];
        }


        //Separe the element configuraiton file identifier from the concrete
        //element identifier
        $elementId = explode('.', $fullElementId, 2);

        //Check if identifier has a correct value
        if (!isset($elementId) || (count($elementId) == 0)) {
            throw new Exception('Incorrect element identifier');
        }

        //The configuration file only contains the options of one element,
        // else get the concrete element of the configuration file
        if (count($elementId) == 1) {
            $elementConfig = isset($this->_elementConfigs[$elementId[0]]) ?
                    $this->_elementConfigs[$elementId[0]] : null;
        } else {
            $elementConfig = $this->_elementConfigs[$elementId[0]]->get($elementId[1]);
        }

        //Check if the elements has been registred
        if (!isset($elementConfig)) {
            throw new Exception('There isn\'t any registred Form Element with the
                identifier: ' . $elementId);
        }

        $elementClass = $elementConfig->get('class');

        if (!isset($elementClass)) {
            throw new Exception('class parameter in configuration file not found,
                the parameter is required, it is the class name to instantiate');
        }


        $elementConfigArray = $elementConfig->toArray();
        unset($elementConfigArray['class']);

        $element = new $elementClass($elementConfigArray);


        $this->_cachedFormElements[$fullElementId] = $element;

        return $element;
    }

    public function getElement($fullElementId) {
        return $this->_prepareElementConfig($fullElementId);
    }

}

