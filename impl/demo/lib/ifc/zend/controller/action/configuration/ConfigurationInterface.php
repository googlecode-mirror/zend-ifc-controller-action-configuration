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
 * ConfigurationInterface
 */

interface ConfigurationInterface {

    /**
     * Set state of configuration
     *
     * @param array $options
     */
    function setOptions(array $options);

    /**
     * Retrieve current options from the configuration
     *
     * @returns array
     */
    //function getOptions();

    /**
     * Set the configurator to which the confuration is attached
     *
     * @param \ifc\zend\controller\action\configurator\ConfiguratorInterface $configurator
     */
    function setConfigurator(\ifc\zend\controller\action\configurator\ConfiguratorInterface $configurator);

    /**
     * Retrieve the configurator to which the configurations is attached
     *
     * @returns \ifc\zend\controller\action\configurator\ConfiguratorInterface
     */
    function getConfigurator();

    /**
     * Strategy pattern: Initialize the configuration
     *
     * @param \Zend_Controller_Request_Abstract $request Request to process
     */
    function init(\Zend_Controller_Request_Abstract $request);

}