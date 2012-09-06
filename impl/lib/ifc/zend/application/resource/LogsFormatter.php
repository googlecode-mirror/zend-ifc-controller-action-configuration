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

namespace ifc\zend\application\resource;

/**
 * LogsFormatter
 */

class LogsFormatter extends \Zend_Application_Resource_ResourceAbstract {
    //put your code here
    //@todo Formatter the logs defined in configuarition file

    public $_explicitType = 'logsFormatter';

    public function init() {

        //@todo load log formatter and apply
        // Create an Zend_Application_Resource_Log inheritance to apply formats because is not
        // possible to apply them later because it is not possible retrieve the writers later
        $bootstrap = $this->getBootstrap();

    }
}

