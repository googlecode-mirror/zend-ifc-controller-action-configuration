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
class Access_TestController extends AccessControllerAbstract {


    public function formValidationAction() {

        $config = Config::getInstance();
        $responseCnf = $config->getResponseConfigurations();

        // Getting the form checkings configurations
        $loginForms = $config->getFormConfiguration();
        $form = $loginForms->getForm(Config::FORM_LOGIN_SECTION);

        $request = $this->getRequest();

        // Checking the validity of submited data
        // If bad submited data then somebody are trying to jump the client side verifications
        if (!$form->isValid($_POST)) {
            $msg = 'INVALID LOGIN because you have jumped the client side validations';

            if ($request->isXmlHttpRequest()) {
                $msg = \Zend_Json::encode(array(
                        $responseCnf[Config::RESPONSE_MSG_NAME_PARAM] => $msg));
                $this->_helper->getHelper('json')->sendJson($msg);
            } else {
                $view->msg = $msg;
            }

        } else {

            $msg = 'LOGIN OK';

            if ($request->isXmlHttpRequest()) {
                $msg = \Zend_Json::encode(array(
                        $responseCnf[Config::RESPONSE_MSG_NAME_PARAM] => $msg));
                $this->_helper->getHelper('json')->sendJson($msg);
            } else {
                $this->view->msg = $msg;
            }
        }
    }

}

