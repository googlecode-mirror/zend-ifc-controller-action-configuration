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


use ifc\zend\modules\access\controller\AccessControllerAbstract;
use \ifc\zend\modules\access\logic\Logger;
use \ifc\zend\modules\access\logic as Logic;
use \ifc\zend\modules\access\Exception;
use ifc\zend\modules\access\Configuration as Config;

/**
 * Description of Access_LogController
 */
class Access_LogController extends AccessControllerAbstract {

    public function init() {

    }

    public function inAction() {


        //If the user has been authenticated and is trying to reauthenticate then
        //the current autentication is cleared
        $authenticator = \Zend_Auth::getInstance();
        if ($authenticator->hasIdentity()) {
            $authenticator->clearIdentity();
        }

        $config = Config::getInstance();
        $responseCnf = $config->getResponseConfigurations();
        $request = $this->getRequest();

        //Reject the request if it is not a POST HTTP method or a configured
        // authentication type (AJAX or HTTP)
        if (!$request->isPost()
                || ((strcasecmp($responseCnf[Config::RESPONSE_TYPE_PARAM],
                        Config::REPONSE_TYPE_HTTP) == 0) && $request->isXmlHttpRequest())
                || ((strcasecmp($responseCnf[Config::RESPONSE_TYPE_PARAM],
                        Config::REPONSE_TYPE_AJAX) == 0) && !$request->isXmlHttpRequest())) {
            // Build the log information about the innapropiated action
            $eventLogBuilder = new Logic\LoginClientUnexpectedAccessMethodEvtLogBuilder();
            $eventLogBuilder->createEventLog('Used method: ' . $request->getMethod() .
                    ' Expected method: ' . $responseCnf['type'] .
                    '// Has Ajax header: ' . (($request->isXmlHttpRequest()) ? 'true' : 'false'),
                    $_SERVER['REMOTE_ADDR']);

            // Register the event into DB
            $entityManager = $config->getDoctrineEntityManager();
            $logger = Logger::getInstance($entityManager);
            $logger->registerEventLog($eventLogBuilder);
            $entityManager->flush();

            $this->getResponse()->setHttpResponseCode(412);
            $exception = new Exception();
            $exception->setMessage($eventLogBuilder);
            throw $exception;
            return;
        }

        // Getting the form checkings configurations
        $loginForms = $config->getFormConfiguration();
        $form = $loginForms->getForm(Config::FORM_LOGIN_SECTION);


        // Checking the validity of submited data
        // If bad submited data then somebody are trying to jump the client side verifications
        if (!$form->isValid($_POST)) {

            // Build the log information about the innapropiated action
            $eventLogBuilder = new Logic\LoginClientValBypassEvtLogBuilder();
            $eventLogBuilder->createEventLog('Username: ' . $form->getValue('username') .
                    ' // Password: ' . $form->getValue('password'),
                    $_SERVER['REMOTE_ADDR']);

            // Register the event into DB
            $entityManager = $config->getDoctrineEntityManager();
            $logger = Logger::getInstance($entityManager);
            $logger->registerEventLog($eventLogBuilder);
            $entityManager->flush();

            $this->getResponse()->setHttpResponseCode(412);
            $exception = new Exception();
            $exception->setMessage($eventLogBuilder);
            throw $exception;
            return;
        } else {

            try {

                $validValues = $form->getValidValues($_POST);
                $authAdapter = new Logic\AuthAdapter(
                                $validValues[Config::FORM_FIELD_USERNAME_PARAM],
                                $validValues[Config::FORM_FIELD_PASSWORD_PARAM],
                                $config->getDoctrineEntityManager());

                $authResult = $authenticator->authenticate($authAdapter);


                if ($authResult->isValid()) {
                    \Zend_Session::regenerateId();
                    $msgs = $authResult->getMessages();

                    if (strcasecmp($responseCnf[Config::RESPONSE_TYPE_PARAM],
                                    Config::REPONSE_TYPE_AJAX) == 0) {
                        $msg = \Zend_Json::encode(array(
                                    $responseCnf[Config::RESPONSE_MSG_NAME_PARAM] => $msgs[0]));
                        $this->_helper->getHelper('json')->sendJson($msg);
                    } else {
                        $responseParams = $responseCnf[Config::RESPONSE_SUCCESSFUL_SECTION];
                        $this->_forward(
                                $responseParams[Config::RESPONSE_ACTION_PARAM],
                                $responseParams[Config::RESPONSE_CONTROLLER_PARAM],
                                $responseParams[Config::RESPONSE_MODULE_PARAM],
                                array($responseCnf[Config::RESPONSE_MSG_NAME_PARAM] => $msgs[0]));
                    }
                } else {
                    $msgs = $authResult->getMessages();

                    if (strcasecmp($responseCnf[Config::RESPONSE_TYPE_PARAM],
                                    Config::REPONSE_TYPE_AJAX) == 0) {
                        $msg = \Zend_Json::encode(array(
                                    $responseCnf[Config::RESPONSE_MSG_NAME_PARAM] => $msgs[0]));
                        $this->_helper->getHelper('json')->sendJson($msg);
                    } else {
                        $responseParams = $responseCnf[Config::RESPONSE_FAILURE_SECTION];
                        $this->_forward(
                                $responseParams[Config::RESPONSE_ACTION_PARAM],
                                $responseParams[Config::RESPONSE_CONTROLLER_PARAM],
                                $responseParams[Config::RESPONSE_MODULE_PARAM],
                                array($responseCnf[Config::RESPONSE_MSG_NAME_PARAM] => $msgs[0]));
                    }
                }
            } catch (\Exception $e) {
                $this->getResponse()->setHttpResponseCode(500);
                $this->_setParam('log_priority', Zend_Log::ALERT);
                throw $e;
            }
        }
    }

    public function outAction() {
        //@todo continuar con la destruccion de la session

        \Zend_Session::expireSessionCookie();

        if (\Zend_Session::isStarted()) {
            \Zend_Session::destroy(true, true);
        }

        $config = Config::getInstance();
        $responseParams = $config->getResponseConfigurations();
        $this->_forward(
                $responseParams[Config::RESPONSE_ACTION_PARAM],
                $responseParams[Config::RESPONSE_CONTROLLER_PARAM],
                $responseParams[Config::RESPONSE_MODULE_PARAM]);
    }

}

