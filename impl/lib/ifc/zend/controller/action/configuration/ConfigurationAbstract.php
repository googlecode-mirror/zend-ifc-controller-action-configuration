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

require_once 'ifc/zend/controller/action/configuration/ConfigurationInterface.php';

/**
 * ConfigurationAbstract
 */
abstract class ConfigurationAbstract implements ConfigurationInterface {

    /**
     * @var array Values for the configuration options
     */
    protected $_options;
    /**
     * @var ConfiguratorInterface
     *   The configurator that has register this configuration
     */
    protected $_configurator;

    /**
     * Set state of configuration
     * Put the configuration parameters and apply the configuration parameters whose
     * method has been declared.
     * The configuraiton methods are declared by the nomenclature set + parameter
     * name for example if the option parameter name is foo, then the configuration
     * parameter will be applied if the concrete configuration class has defined
     * a method with the name setFoo (the same as setfoo)
     *
     * @param array $options
     */
     public function setOptions(array $options) {

        $this->_options = $options;
        foreach ($options as $key => $value) {
            $method = 'set' . strtolower($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }

    /**
     * Retrieve current options from the configuration
     *
     * @returns array
     */
    /*
    protected function getOptions() {
        return $this->_options;
    }
/**/

    /**
     * Set the configurator to which the confuration is attached
     *
     * @param \ifc\zend\controller\action\configurator\ConfiguratorInterface $configurator
     */
    public function setConfigurator(\ifc\zend\controller\action\configurator\ConfiguratorInterface $configurator) {
        $this->_configurator = $configurator;
    }

    /**
     * Retrieve the configurator to which the configurations is attached
     *
     * @returns \ifc\zend\controller\action\configurator\ConfiguratorInterface
     */
    public function getConfigurator() {
        return $this->_configurator;
    }

}