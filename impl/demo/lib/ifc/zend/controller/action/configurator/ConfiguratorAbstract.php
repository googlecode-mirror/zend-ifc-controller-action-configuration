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

require_once 'ifc/zend/controller/action/configurator/ConfiguratorInterface.php';

/**
 * Abstract base class for controllers actions configurator classes
 */
abstract class ConfiguratorAbstract implements ConfiguratorInterface {

    /**
     * @var array Related configurations to the scope of module
     *            Key => Value array with:
     *            Key: identifier of the configuration
     *            Value: Instance of the configuration class.
     *
     */
    protected $_moduleConfigs;
    /**
     * @var array Related configurations to the scope of controllers
     *            Key => Value array with:
     *            Key: identifier of the configuration
     *            Value: Instance of the configuration class.
     *
     */
    protected $_controllersConfigs;
    /**
     * @var array Related configurations to the scope of actions
     *            Key => Value array with:
     *            Key: identifier of the configuration
     *            Value: Instance of the configuration class.
     *
     */
    protected $_actionsConfigs;
    /**
     * @var array If the configurations is running the initialization but yet
     *            finished (detection circular dependency and replacement
     *            configurations)
     *            The methods of this class put always value TRUE with the correspondant
     *            keys for identify the configurations that are running the initilization;
     *            when the initialization process has finished, unset the keys;
     *            so only is needed check the existence of the keys and is not
     *            needed check the value.
     */
    protected $_initializing;
    /**
     * @var array The configurations that have been initialized
     */
    protected $_initialized;

    /**
     *    Initilialize all the configurations registered by
     *    this configurator that apply to the module, controller and
     *    action determined by the request
     *
     *    @param \Zend_Controller_Request_Abstract $request Request to process
     *
     */
    public function runAll(\Zend_Controller_Request_Abstract $request) {

        //Execute configurations that apply to module scope
        if (isset($this->_moduleConfigs)) {
            foreach ($this->_moduleConfigs as $name => $config) {
                $config->init($request);
            }
        }

        //Execute configurations that apply to controller scope
        $controllerName = $request->getControllerName();

        if (isset($this->_controllersConfigs[$controllerName])) {
            foreach ($this->_controllersConfigs[$controllerName] as $name => $config) {
                $config->init($request);
            }
        }

        //Execute configurations that apply to action scope
        $actionName = $request->getActionName();

        if (isset($this->_actionsConfigs[$controllerName][$actionName])) {
            foreach ($this->_actionsConfigs[$controllerName][$actionName] as $name => $config) {
                $config->init($request);
            }
        }
    }

    /**
     * Initialize the configuration registered with suitable name.
     * Moreover to allow to initilize a concrete configuration, the method
     * allow that configurations that depends of the initilization
     * of other configurations can be run before them.
     *
     * @param \Zend_Controller_Request_Abstract $request Request to process
     * @param string $configClassName Configuration complete class name (name with full namespace)
     */
    public function run(\Zend_Controller_Request_Abstract $request, $configClassName) {

        //If the config exist in module scope then execute this
        if (isset($this->_moduleConfigs[$configClassName])) {
            $this->_moduleConfigs[$configClassName]->init($request);
        }

        $controllerName = $request->getControllerName();

        //If the config exist in controller scope then execute this
        if (isset($this->_controllersConfigs[$controllerName][$configClassName])) {
            $this->_controllersConfigs[$controllerName][$configClassName]->init($request);
        }

        $actionName = $request->getActionName();
        //If the config exist in action scope then execute this
        if (isset($this->_actionsConfigs[$controllerName][$actionName][$configClassName])) {
            $this->_actionsConfigs[$controllerName][$actionName][$configClassName]->init($request);
        }
    }

    /**
     * Registers a configuration into indicated scope.
     * See this method in the definiton of the interface:
     * \ifc\zend\controller\action\ConfigurationInterface for extended desciption.
     *
     * @param string|\ifc\zend\controller\action\ConfigurationInterface $configClass
     *              Configuration complete class name (name with full namespace) or
     *              instace of a class that implements \ifc\zend\controller\action\ConfigurationInterface
     * @param array Key array with options of the configuration (Null if configuration
     *              does not need options) or first argument is an object not a string
     * @param mixed $controllerName Controller name
     * @param mixed $actionName Action name
     * @see ConfiguratorIterface
     * @throws \ifc\zend\controller\action\configurator\Exception
     */
    public function registerConfiguration($configClass, $configDef = null, $controllerName = null, $actionName = null) {

        //Basic check of the consistency scope
        if ($controllerName === null && $actionName !== null) {
            require_once 'ifc/zend/controller/action/configurator/Exception.php';
            throw new \ifc\zend\controller\action\configurator\Exception(
                    'Incorrect scope definition; action cannot defined without a controller');
        }

        if ($configClass instanceof \ifc\zend\controller\action\ConfigurationInterface) {
            $config = $configClass;
            $configClassName = get_class($configClass);
        } else {
            $config = $this->_createConfiguration($configClass, $configDef);
            $configClassName = $configClass;
        }

        if ($controllerName !== null) {
            if ($actionName !== null) {
                if (isset($this->_initializing[$controllerName][$actionName])) {
                    require_once 'ifc/zend/controller/action/configurator/Exception.php';
                    throw new \ifc\zend\controller\action\configurator\Exception('
                            It is not possible register this configuration because a same
                            configurations is running the initilization process');
                }

                $this->_actionsConfigs[$controllerName][$actionName][$configClassName] = $config;
            } else {
                if (isset($this->_initializing[$controllerName])) {
                    require_once 'ifc/zend/controller/action/configurator/Exception.php';
                    throw new \ifc\zend\controller\action\configurator\Exception('
                            It is not possible register this configuration because a same
                            configurations is running the initilization process');
                }

                $this->_controllersConfigs[$controllerName][$configClassName] = $config;
            }
        } else {
            if (isset($this->_initializing[$configClassName])) {
                require_once 'ifc/zend/controller/action/configurator/Exception.php';
                throw new \ifc\zend\controller\action\configurator\Exception('
                        It is not possible register this configuration because a same
                        configurations is running the initilization process');
            }

            $this->_moduleConfigs[$configClassName] = $config;
        }
    }

    /**
     * Registers the configurations.
     * See this method in Ifc_Zend_Controller_Action_Configurator_Interface for
     * extended desciption and the hoped configuration array format.
     *
     * @param array $configurations Key array that specifiy the configurations.
     * @param bool $add True to add the configurations supplied to existing
     *              configurations false to replace.
     * @see ConfiguratorIterface
     * @throws \ifc\zend\controller\action\configurator\Exception
     */
    public function registerConfigurations(array $configurations, $add = true) {

        if (!$add) {
            if (count($this->_initializing) == 0) {
                require_once 'ifc/zend/controller/action/configurator/Exception.php';
                throw new \ifc\zend\controller\action\configurator\Exception('
                    It is not possible register new configurations with deleting
                    the existing if there is some configuration is running the
                    initilization process');
            }

            unset($this->_moduleConfigs, $this->_controllersConfigs,
                    $this->_actionsConfigs, $this->_initialized);
        }

        $configurations = $this->_disjointConfigsDefinitionsByScope($configurations);

        if (isset($configurations['module'])) {
            foreach ($configurations['module'] as $name => $options) {
                $this->registerConfiguration($name, $options);
            }
        }

        if (isset($configurations['controllers'])) {
            foreach ($configurations['controllers'] as $controllerName => $configs) {
                foreach ($configs as $name => $options) {
                    $this->registerConfiguration($name, $options, $controllerName);
                }
            }
        }

        if (isset($configurations['actions'])) {
            foreach ($configurations['actions'] as $controllerName => $actions) {
                foreach ($actions as $actionName => $configs) {
                    foreach ($configs as $name => $options) {
                        $this->registerConfiguration($name, $options,
                                $controllerName, $actionName);
                    }
                }
            }
        }
    }

    /**
     * Returns an key array with configurations names (key) and
     * ConfigurationInterface objects (values).
     * The array contains the subarrays with the configurations of controller and
     * action scope under the correspondant keys.
     *
     * @returns array
     */
    public function getConfigurations() {

        $ctrlsKey = $this->getControllersKeyScope();
        $actionsKey = $this->getActionsKeyScope();

        $configurations = $this->_moduleConfigs;
        $configurations[$ctrlsKey] = $this->_controllersConfigs;

        foreach ($this->_actionsConfigs as $controllerName => $actions) {
            $configurations[$ctrlsKey][$controllerName][$actionsKey] = $actions;
        }

        return $configurations;
    }

    /**
     * Disjoints definitions configurations by scopes.
     * Array with the keys of module, controller and actions, that contains the
     * configurations of each scope, respectively.
     * If the scope doesn't have configurations then null array is returned
     * under the correspondant key
     *
     * controllers is a subarray with controllers name as key.
     * actions is a subarray of with controllers name and actions name with keys
     * and subkeys respectively
     *
     * @param array $configsDefinitions Configurations definitions
     * @return array If there are return the definitions, otherwise null
     * @see ConfiguratorInterface registerConfigurations
     *      method for hoped input configurations array
     */
    protected function _disjointConfigsDefinitionsByScope(array $configsDefinitions) {

        $moduleConfigsDef = array_diff_key($configsDefinitions,
                        array($this->getControllersKeyScope() => null));

        //Assigns null if there are not configurations for the module scope
        $moduleConfigsDef = (count($moduleConfigsDef) > 0) ? $moduleConfigsDef : null;

        $ctrlsConfigsDef = null;
        $actionsConfigsDef = null;

        if (isset($configsDefinitions[$this->getControllersKeyScope()])) {
            $ctrlsAndActionsConfigsDef = $configsDefinitions[$this->getControllersKeyScope()];
            $actionsKey = $this->getActionsKeyScope();
            $actionsSubstract = array($actionsKey => null);

            foreach ($ctrlsAndActionsConfigsDef as $controllerName => $ctrlConfigs) {
                //temp var
                $ctrlConfigsDef = array_diff_key($ctrlConfigs, $actionsSubstract);

                //Check if controller has configurations
                if (count($ctrlConfigsDef) > 0) {
                    $ctrlsConfigsDef[$controllerName] = $ctrlConfigsDef;
                }

                //Check if controller has actions configurations
                if (isset($ctrlConfigs[$actionsKey])) {
                    foreach ($ctrlConfigs[$actionsKey] as $actionName => $actionConfigs) {
                        //Check if action has configurations
                        if (count($actionConfigs > 0)) {
                            $actionsConfigsDef[$controllerName][$actionName] = $actionConfigs;
                        }
                    }
                }
            }
        }

        return array('module' => $moduleConfigsDef,
            'controllers' => $ctrlsConfigsDef,
            'actions' => $actionsConfigsDef);
    }

    /**
     * Creates the configuration with the specified options.
     * The configurations are classes that have to implement the interface
     * \ifc\zend\controller\action\ConfigurationInterface
     *
     * @param string $configClassName Configuration complete class name (name with full namespace)
     * @param mixed null|array Options of the configuration
     * @return ConfigurationInterface
     * @throws \ifc\zend\controller\action\configurator\Exception When the indicated
     *      configuration class name not implements the interface
     *      \ifc\zend\controller\action\ConfigurationInterface
     */
    protected function _createConfiguration($configClassName, array $options = null) {

        $config = new $configClassName();

        if (!$config instanceof \ifc\zend\controller\action\configuration\ConfigurationInterface) {
            require_once 'ifc/zend/controller/action/configurator/Exception.php';
            throw new \ifc\zend\controller\action\configurator\Exception(
                    sprintf('The class: %s not implements the Interface:
                        \ifc\zend\controller\action\ConfigurationInterface', $configClassName));
        }

        if ($options !== null) {
            $config->setOptions($options);
        }

        $config->setConfigurator($this);

        return $config;
    }

}