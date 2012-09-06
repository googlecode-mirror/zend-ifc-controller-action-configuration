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

namespace ifc\zend\modules\access;

/**
 * Description of Configuration
 * @todo Comment methods of this class
 */
class Configuration extends \ifc\zend\module\Configuration {
    //// Used keys into configuration file for module configuration parameters //
    // Key for registred Doctrine Configuration to use
    const DOCTRINE_CONFIGURAITON_KEY = 'doctrineConfigKey';

    //// Keys for response parameters
    // Response root key
    const RESPONSE_ROOT = 'response';
    // Response group for multiple response configuraiton in same action
    // * Response succcessful
    const RESPONSE_SUCCESSFUL_SECTION = 'success';
    // * Response failure parent key
    const RESPONSE_FAILURE_SECTION = 'fail';
    // Response parameters
    const RESPONSE_TYPE_PARAM = 'type';
    const RESPONSE_MSG_NAME_PARAM = 'msgParamName';
    const RESPONSE_MODULE_PARAM = 'module';
    const RESPONSE_CONTROLLER_PARAM = 'controller';
    const RESPONSE_ACTION_PARAM = 'action';

    //// Keys for response parameters
    // Forms root key
    const FORM_ROOT = 'forms';
    // Key for registred Forms Configuration to use
    const FORM_CONFIGURAITON_KEY = 'configKey';
    // Form fields parent key
    const FORM_FIELDS_SECTION = 'fields';
    // Form fileds
    const FORM_FIELD_USERNAME_PARAM = 'username';
    const FORM_FIELD_PASSWORD_PARAM = 'password';
    // Key form configuration for login validation -- used for reference
    // to form in ifc\zend\controller\action\configuration\form\FormConfigsFile
    // This parameter is fixed, not parametrizable
    const FORM_LOGIN_SECTION = 'login';

    ///////////////////////////////////////////////////////////////////////////
    // Constants to use for some parameters not configurable in configuarion file //
    const USER_FORM_FIELD_NAME = 'username';
    ///////////////////////////////////////////////////////////////////////////
    // Constant values to use for some configuarion parameters /////////////////
    const REPONSE_TYPE_HTTP = 'HTTP';
    const REPONSE_TYPE_AJAX = 'AJAX';

    private function __construct() {
        $this->_moduleConfigParamsKey = 'IFC_ACCESS_MODULE';
    }

    public static function getInstance() {
        if (!isset(Configuration::$_instance)) {
            Configuration::$_instance = new Configuration();
        }

        return Configuration::$_instance;
    }

    public function getDoctrineEntityManager() {
        return $this->getRegistredConfiguration(
                                Configuration::DOCTRINE_CONFIGURAITON_KEY)
                        ->getEntityManager();
    }

    public function getResponseConfigurations() {
        return $this->getParameter(Configuration::RESPONSE_ROOT);
    }

    public function getFormConfiguration() {
        return $this->getRegistredConfiguration(
                        Configuration::FORM_CONFIGURAITON_KEY,
                        Configuration::FORM_ROOT);
    }

    public function getFormFields() {
        return $this->getParameter(
                        Configuration::FORM_FIELDS_SECTION,
                        Configuration::FORM_ROOT);
    }

}

