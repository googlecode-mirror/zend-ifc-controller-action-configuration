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

namespace ifc\zend\modules\test01;

class Configuration extends \ifc\zend\module\Configuration {
    //// Used keys into configuration file for module configuration parameters //
    const DOJO_BODY_CSS = 'dojo-body-css';

    private function __construct() {
        $this->_moduleConfigParamsKey = 'TEST01_MODULE';
    }

    public static function getInstance() {
        if (!isset(Configuration::$_instance)) {
            Configuration::$_instance = new Configuration();
        }

        return Configuration::$_instance;
    }

    public function getDojoBodyCSS() {
    	try {
        	return $this->getParameter(
                                Configuration::DOJO_BODY_CSS);
    	} catch (Exception $e) {
    		// The module configuration has not been defined
    		return null;
    	}
    }

}

