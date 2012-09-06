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

/**
 * Description of ManagerController
 * @todo document the class and method with comments
 *
 */
class Error_ManagerController extends \ifc\zend\controller\ControllerActionAbstract {

    protected function _getExceptionsInfo() {
        $message = '';
        $exceptions = $this->getResponse()->getException();

        if (is_array($exceptions)) {
            if ($this->getInvokeArg('displayExceptions') == true) {
                $newLineSymbol = '<br/>';
            } else {
                $newLineSymbol = PHP_EOL;
            }

            foreach ($exceptions as $exception) {
                $message .= get_class($exception) . ' in:' . $exception->getFile() .
                        '(' . strval($exception->getLine()) . ')' . $newLineSymbol;
                $message .= 'Code: ' . strval($exception->getCode()) . $newLineSymbol;
                $message .= 'Message: ' . $exception->getMessage() . $newLineSymbol;
                $message .= 'Stacktrace:' . $newLineSymbol;
                $message .= $exception->getTraceAsString() . $newLineSymbol;
            }
        }

        return $message;
    }

    protected function _reportExceptions($logPriority) {
        $exInfoMsg = $this->_getExceptionsInfo();

        if (!empty($exInfoMsg)) {
            if ($this->getInvokeArg('displayExceptions') == true) {
                $this->view->exceptionsMsgs = $exInfoMsg;
            } else {
                $this->getLogger()->log($exInfoMsg, $logPriority);
            }
        }
    }

    protected function _reportHTTPStatusCode() {
        $httpCode = $this->getResponse()->getHttpResponseCode();
        $actionName = 'httpStatus' . strval($httpCode);

        if (method_exists($this, $actionName . 'Action')) {
            $this->_forward($actionName);
            return true;
        } else {
            return false;
        }
    }

    public function errorAction() {

        $errors = $this->_getParam('error_handler');
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->_reportExceptions(Zend_Log::NOTICE);
                $this->render('http-status404');
                break;
            default:
                if (!$this->_reportHTTPStatusCode()) {
                    $this->_reportExceptions(Zend_Log::CRIT);
                    $this->getResponse()->setHttpResponseCode(500);
                    $this->render('http-status500');
                }
                break;
        }
    }

    public function httpStatus405Action() {
        $this->getResponse()->setHttpResponseCode(405);
        $this->_reportExceptions(Zend_Log::NOTICE);
    }

    public function httpStatus412Action() {
        $this->getResponse()->setHttpResponseCode(412);
        $this->_reportExceptions(Zend_Log::NOTICE);
    }

    public function httpStatus500Action() {
        $this->getResponse()->setHttpResponseCode(500);
        $this->_reportExceptions($this->_getParam('log_priority'));
    }

    public function __call($method, $args) {
        if (method_exists($this, $method)) {
            $this->$method($args);
        }
    }

}

