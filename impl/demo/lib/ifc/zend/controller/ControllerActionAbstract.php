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

namespace ifc\zend\controller;

/**
 * Description of ControllerActionAbstract
 */
abstract class ControllerActionAbstract extends \Zend_Controller_Action {

    protected function getLogger() {
        if ($this->getInvokeArg('bootstrap')->hasResource('log')) {
            return $this->getInvokeArg('bootstrap')->getResource('log');
        } else {
            $logger = new \Zend_Log();
            $logger->addWriter(new \Zend_Log_Writer_Null());
            return $logger;
        }
    }

}
