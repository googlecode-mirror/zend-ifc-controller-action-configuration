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

require_once 'ifc/zend/controller/action/configurator/ConfiguratorAbstract.php';

/**
 * Basic controller action configurator class
 */
class Basic extends ConfiguratorAbstract {

    /**
     * @var Zend_Log
     *      Logger object to log events
     */
    protected $_logger;

    public function __construct(\Zend_Log $logger = null) {

        if ($logger === null) {
            $this->_logger = new \Zend_Log(new \Zend_Log_Writer_Null());
        }
    }

    /**
     * Set the state of the configurator
     * This method does not do any actions because this configurator does not have
     * state options
     *
     * @param array $options
     */
    public function setOptions(array $options) {

    }

    /**
     * Retrieve the current options from the configuratior
     * This method return empty array, because this configurator does not have
     * state options
     *
     * @return array
     */
    public function getOptions() {
        return array();
    }

    /**
     * Retrieve the key used in the configuraitons definition array to refer to
     * the controllers configuraitons scope
     *
     * @return string
     */
    public function getControllersKeyScope() {
        return 'controllers';
    }

    /**
     * Retrieve the key used in the configuraitons definition array to refer to
     * the actions configuraitons scope
     * Note: the scope of action always is referenced under a controller.
     *
     * @return string
     */
    public function getActionsKeyScope() {
        return 'actions';
    }

}

