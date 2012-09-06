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

namespace ifc\zend\controller\action\configurator;

/**
 * ConfiguratorInterface
 */
interface ConfiguratorInterface {

    public function __construct();

    /**
     * Set the state of the configurator
     *
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * Retrieve the current options from the configuratior
     *
     * @returns array
     */
    public function getOptions();

    /**
     * Initilialize all the configurations registered by
     * this configurator that apply to the module, controller and
     * action determined by the request
     *
     * @param \Zend_Controller_Request_Abstract $request Request to process
     *
     */
    public function runAll(\Zend_Controller_Request_Abstract $request);

    /**
     * Initialize the configuration registered with suitable name.
     * Moreover to allow to initilize a concrete configuration, the method
     * allow that configurations that depends of the initilization
     * of other configurations can be run before them.
     *
     * @param \Zend_Controller_Request_Abstract $request Request to process
     * @param string $configName Name that identify the configuration
     */
    public function run(\Zend_Controller_Request_Abstract $request, $configName);


    /**
     * Registers a configuration into indicated scope.
     * If scope is not indicated (null) then the configuration will be registered
     * in module scope.
     *
     * @param string|\ifc\zend\controller\action\ConfigurationInterface $configClass
     *              Configuration complete class name (name with full namespace) or
     *              instace of a class that implements
     *              \ifc\zend\controller\action\ConfigurationInterface
     * @param array $configDef Key array with options of the configuration (Null
     *              if configuration
     *              does not need options) or $configClass argument is an object not a string
     * @param mixed $controllerName Controller name
     * @param mixed $actionName Action name
     */
    public function registerConfiguration($configClass, $configDef = null, $controllerName = null, $actionName = null);

    /**
     * Registers the configurations.
     * The configurations are specified with a key array with names to identify
     * the configuration class and key subarrays with their options (null if the
     * configuration has not needed options) or instances of classes that implements
     * Ifc_Zend_Controller_Action_ConfigurationInterface. The controller and action
     * scope configurations have to be assigned with key subarrays under the
     * correspondant controllers and action keys scope.
     *
     * The configurations of controllers under correspondant key is a multidimensional
     * key array, using controller name, with configurations to apply to each controller.
     *
     * The configurations of actions act of the same manner that controllers configurations
     * but these are under the configurations subarray of controller which belong.
     *
     * Example configurations array:
     * 'controllers' is the key used to register the configuration of controllers scope.
     * 'actions' is the key used to register the configurations of actions scope
     * 'config' is a name that identify a class that implements Ifc_Zend_Controller_Action_ConfigurationInterface
     *
     * array(
     *      'config' => array(
     *          'option1' => 'value option1 in module'
     *      ),
     *      'controllers' => array(
     *          'controller1' => array(
     *              'config' => array(
     *                 'option1' => 'value otpion1 in controller1',
     *                 'option2' => 'value option2 in controller2'
     *              )
     *          ),
     *          'controller2' => array(
     *              'config' => array(
     *                  'option1'=> 'value option1 in controller2'
     *              ),
     *              'actions' => array(
     *                  'action1' => array(
     *                      'config' => array(
     *                          'option1' => 'value option1 in action1'
     *                      ),
     *                      'otherconfig' => array(
     *                          'option1' => 'value option1 of other config in action1'
     *                      )
     *                  )
     *              )
     *          )
     *      )
     * )
     *
     * @param array $configurations Key array that specifiy the configurations.
     * @param bool $add True to add the configurations supplied to existing
     *              configurations false to replace.
     */
    public function registerConfigurations(array $configurations, $add = true);

    /**
     * Returns an key array with configurations names (key) and
     * Ifc_Zend_Controller_Action_Configuration objects (values).
     * The array contains the subarrays with the configurations of controller and
     * action scope under the correspondant keys.
     *
     * @returns array
     */
    public function getConfigurations();

    /**
     * Retrieve the key used in the configuraitons definition array to refer to
     * the controllers configuraitons scope
     *
     * @return string
     */
    public function getControllersKeyScope();

    /**
     * Returns the key used to reference the action scope in array of configurations.
     * Note: the scope of action always is referenced under a controller.
     *
     * @return string
     */
    public function getActionsKeyScope();
}
