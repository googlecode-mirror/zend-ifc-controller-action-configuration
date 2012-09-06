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

namespace ifc\zend\controller\plugin\module;

/**
 * ActionHelper
 *
 * Register the actions helpers used in the module
 */
class ActionHelper extends \Zend_Controller_Plugin_Abstract {

    /**
     * Register the Actions Helpers (@link \Zend_Controller_Action_Helper_Abstract)
     * that will be used to manage the configuration of the requested modules
     *
     * Called before an action is dispatched by Zend_Controller_Dispatcher,
     * @see  \Zend_Controller_Plugin_Abstract {@link \Zend_Controller_Plugin_Abstract::preDispatch}
     *
     * @param  \Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(\Zend_Controller_Request_Abstract $request) {

        $bootstrap = \Zend_Controller_Front::getInstance()->getParam('bootstrap');
        $moduleName = $request->getModuleName();

        $moduleConfigOptions = $bootstrap->getResource('ifc\zend\application\resource\ModuleConfigurator');

        $actionHelpers = $moduleConfigOptions->getActionHelpers($moduleName);

        // Check action helpers to use in module
        if ($actionHelpers !== null) {
            // Register the module action helpers
            foreach ($actionHelpers as $className => $path) {
                include_once $path;
                \Zend_Controller_Action_HelperBroker::addHelper(new $className);
            }
        }
    }

}

