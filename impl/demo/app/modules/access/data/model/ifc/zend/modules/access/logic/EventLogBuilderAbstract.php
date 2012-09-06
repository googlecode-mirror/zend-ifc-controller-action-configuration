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

namespace ifc\zend\modules\access\logic;

/**
 * Description of EventLogBuilderAbstract
*/

abstract class EventLogBuilderAbstract implements EventLogBuilderInterface {

    /**
     * @var \ifc\zend\modules\access\data\EventLog
     *  The EventLog objet to build with the concrete information to log
     */
    protected $_eventLog;

    public function getEventLog() {
        return $this->_eventLog;
    }
}


