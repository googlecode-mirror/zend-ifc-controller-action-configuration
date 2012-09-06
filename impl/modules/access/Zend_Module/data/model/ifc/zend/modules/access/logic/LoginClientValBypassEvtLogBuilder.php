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

use ifc\zend\modules\access\data\EventLog;

/**
 * Creates an EventLog associated to user login process to inform that
 * the user has done a bypass of client side validations (Browser script
 * form validations)
 */

class LoginClientValBypassEvtLogBuilder extends EventLogBuilderAbstract {

    protected static $_type = 1;
    protected static $_code = 307;
    protected static $_name = 'User Login: Client side validation bypass';

    public function createEventLog($information, $ipAddress = null) {

        $this->_eventLog = new EventLog();

        $this->_eventLog->setEventType(LoginClientValBypassEvtLogBuilder::$_type);
        $this->_eventLog->setEventCode(LoginClientValBypassEvtLogBuilder::$_code);
        $this->_eventLog->setEventName(LoginClientValBypassEvtLogBuilder::$_name);
        $this->_eventLog->setEventInfo($information);
        $this->_eventLog->setIpAddress($ipAddress);
    }
}

