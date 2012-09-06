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

namespace ifc\zend\application\resource\controller\plugin;

/**
 * ErrorHandler
 * Configuration class of Zend_Controller_Plugin_ErrorHandler for using in application
 */
class ErrorHandler extends \Zend_Application_Resource_ResourceAbstract {

    public $_explicitType = 'Zend_Controller_Plugin_ErrorHandler';

    /**
     * @var array The options to configure the controller plugin error handler
     */
    protected $_errHandlerOptions = array();

    public function init() {
        $bootstrap = $this->getBootstrap();
        $bootstrap->bootstrap('frontController');

        $frontController = $bootstrap->getResource('frontController');

        if (!$frontController->hasPlugin('\Zend_Controller_Plugin_ErrorHandler')) {
            $plugin = new \Zend_Controller_Plugin_ErrorHandler($this->_errHandlerOptions);
            $frontController->registerPlugin($plugin);
        }

        return $plugin;
    }

    public function setModule($moduleName) {
        $this->_errHandlerOptions['module'] = $moduleName;
    }

    public function setController($controllerName) {
        $this->_errHandlerOptions['controller'] = $controllerName;
    }

    public function setAction($actionName) {
        $this->_errHandlerOptions['action'] = $actionName;
    }

}

