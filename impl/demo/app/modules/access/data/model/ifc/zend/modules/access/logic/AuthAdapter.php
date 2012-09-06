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
 * Authenticate adapter to integrate with Zend Authentication architectures
 */
class AuthAdapter implements \Zend_Auth_Adapter_Interface {

    /**
     * @var string The key used to store the doctrine configuration into global
     *      Zend Registry
     */
    protected $_doctrineConfigRegKey = 'Doctrine';

    /**
     * @var \Doctrine\ORM\EntityManager Manager for data base access
     */
    protected $_doctrineEntityManager;

    /**
     * @var Zend_Translate Translator to use for message translation
     */
    protected $_translator = null;

    /**
     * @var string The identifier of the user to autenticate
     */
    protected $_username;

    /**
     * @var string The password to validate the user autentication
     */
    protected $_password;

    /**
     * Create a new Zend_Auth_Adapater with the stored or supplied Doctrine configuration
     * to authenticate an user with the supplied username and password
     *
     * @param string $username
     * @param string $password
     * @param mixed string | \ifc\zend\controller\action\configuration\database\DoctrineConfiguration $doctrine
     *      The string used to store de DoctrineConfiguration instance in Zend_Registry
     *      or a valid and configured instance of Doctrine\ORM\EntityManager to use
     */
    public function __construct($username, $password, $doctrine = null) {

        if (!isset($doctrine)) {
            $this->_doctrineEntityManager =
                    \Zend_Registry::get($this->_doctrineConfigRegKey)->getEntityManager();
        } else if (is_string($doctrine)) {
            $this->_doctrineConfigRegKey = $doctrine;
            $this->_doctrineEntityManager =
                    \Zend_Registry::get($this->_doctrineConfigRegKey)->getEntityManager();
        } else if (is_a($doctrine, '\Doctrine\ORM\EntityManager')) {
            $this->_doctrineEntityManager = $doctrine;
        } else {
            throw new \Zend_Auth_Exception('Incorrect parameter type; "doctrine" parameter
                have to be string or \\ifc\\zend\\controller\\action\\configuration\\database\\DoctrineConfiguration');
        }

        $this->_username = $username;
        $this->_password = $password;

        $registry = \Zend_Registry::getInstance();
        if ($registry->offsetExists('Zend_Translate')) {
            $this->_translator = $registry->get('Zend_Translate');
        }
    }

    /**
     *
     * @param \ifc\zend\modules\access\data\User $user
     */
    protected function _getUserSaltAndSplitterFactor(\ifc\zend\modules\access\data\User $user) {
        $mixerCnf = explode('.', $user->getAccessPasswordSalt(), 2);

        if (count($mixerCnf) == 1) {
            array_unshift($mixerCnf, 1);
        }

        return $mixerCnf;
    }

    /**
     * Wrapper of message translation checking if there is a translator
     *
     * @param type $msg Message to translate
     * @return string Message translation if there is a translator, otherwise
     *      original message
     */
    protected function _($msg) {
        return isset($this->_translator) ? $this->_translator->_($msg) : $msg;
    }

    /**
     * Set the Zend_Translate object to use
     *
     * @param \Zend_Translate $translator
     */
    public function setTranslator(\Zend_Translate $translator) {
        $this->_translator = $translator;
    }

    /**
     *
     */
    public function authenticate() {
        $user = $this->_doctrineEntityManager
                ->getRepository('\ifc\zend\modules\access\data\User')
                ->findOneByUserName($this->_username);

        if (!isset($user)) {
            // Build the log information about the inexistent account
            $eventLogBuilder = new LoginClientIncorrectUsernameEvtLogBuilder();
            $eventLogBuilder->createEventLog('Submited username: ' . $this->_username,
                    $_SERVER['REMOTE_ADDR']);

            // Register the event into DB
            $logger = Logger::getInstance($this->_doctrineEntityManager);
            $logger->registerEventLog($eventLogBuilder);
            $this->_doctrineEntityManager->flush();

            return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND,
                            null,
                            array($this->_('Invalid username or password')));
        } else {
            $saltMixer = $this->_getUserSaltAndSplitterFactor($user);
            $securedPassword = \ifc\util\security\KeyManager::pwdAndSaltMixerByPwdLength($this->_password,
                            $saltMixer[1], $saltMixer[0]);

            //Correct user validation
            if ($user->validateAccess($securedPassword[1])) {
                return new \Zend_Auth_Result(\Zend_Auth_Result::SUCCESS,
                                $this->_username,
                                array($this->_('Login access Ok')));
            } else if (!$user->isStateActive()) { // invalid acces because the account is not in active state
                $logger = Logger::getInstance($this->_doctrineEntityManager);

                // Inactive account state
                if ($user->isStateInactive()) {
                    // Build the log information
                    $eventLogBuilder = new LoginClientInactiveAccountEvtLogBuilder();
                    $eventLogBuilder->createEventLog(null,
                            $_SERVER['REMOTE_ADDR']);

                    // Register the event into DB
                    $logger->registerUserEventLog($eventLogBuilder, $user);
                    $this->_doctrineEntityManager->flush();

                    return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_UNCATEGORIZED,
                                    $this->_username,
                                    array($this->_('Your user account has been inactive, please contact with the web master')));
                } else if ($user->isStateBlocked()) {
                    // Build the log information
                    $eventLogBuilder = new LoginClientBlockedAccountEvtLogBuilder();
                    $eventLogBuilder->createEventLog(null,
                            $_SERVER['REMOTE_ADDR']);

                    // Register the event into DB
                    $logger->registerUserEventLog($eventLogBuilder, $user);
                    $this->_doctrineEntityManager->flush();

                    return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_UNCATEGORIZED,
                                    $this->_username,
                                    array($this->_('Your user account has been blocked because somebody has tried to access
                                            several times with incorrect password, the account will get unlocked in a while automatically;
                                            if you had not been trying to access to the system, then please contact with the web master
                                            becasue your account has been atacked')));
                } else if ($user->isStateRegistredNotConfirmed()) {

                    // Build the log information
                    $eventLogBuilder = new LoginClientUnconfirmedRegistrationEvtLogBuilder();
                    $eventLogBuilder->createEventLog(null,
                            $_SERVER['REMOTE_ADDR']);

                    // Register the event into DB
                    $logger->registerUserEventLog($eventLogBuilder, $user);
                    $this->_doctrineEntityManager->flush();

                    return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_UNCATEGORIZED,
                                    $this->_username,
                                    array($this->_('Your registered user account has not been confirmed, please check
                                            your e-mail account to confirm the registration; if one hour has passed and
                                            you have not received any e-mail, please contact with the web master')));
                } else {
                    throw new \Zend_Auth_Exception('Unknow user state. Incorrect user login access');
                }
            } else {
                //Generate the initialization vector to encrypt the password submited
                //to not store it in plain text in the log and avoid that an attacker
                //can get some hints to find the correct password
                $initvector = $user->getAccessPasswordSalt();
                if (strlen($initvector) < 16) {
                    $initvector = str_pad($initvector, 16, '0', STR_PAD_RIGHT);
                } else {
                    $initvector = substr($initvector, 0, 16);
                }

                $cipheredPwd = openssl_encrypt($this->_password, 'aes256',
                        $user->getAccessPasswordSalt() . $this->_username,
                        false, $initvector);

                // Build the log information about the incorrect password submited
                $eventLogBuilder = new LoginClientIncorrectPasswordEvtLogBuilder();
                $eventLogBuilder->createEventLog('Submited password: ' . $cipheredPwd,
                        $_SERVER['REMOTE_ADDR']);

                $logger = Logger::getInstance($this->_doctrineEntityManager);
                $logger->registerUserEventLog($eventLogBuilder, $user);
                $this->_doctrineEntityManager->flush();

                return new \Zend_Auth_Result(\Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID,
                                $this->_username,
                                array($this->_('Invalid username or password')));
            }
        }
    }

}
