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

namespace ifc\zend\modules\access\data;

/**
 * User
 *
 * @Entity
 * @Table(name="IFCMACC_USERS")

 */
class User {
    const INACTIVE_STATE = 1;
    const BLOCKED_STATE = 2;
    const REGNOTCONFIRMED_STATE = 4;
    const FORCECHGACCPWD_STATE = 64;
    const ACTIVE_STATE = 128;

    /**
     * @var integer
     *
     * @Column(name="ID", type="integer", nullable=false, unique=true)
     * @GeneratedValue(strategy="AUTO")
     * @Id
     */
    private $id;
    /**
     * @var string
     *
     * @Column(name="USR_NAME", type="string", length=30, nullable=false, unique=true)
     */
    private $userName;
    /**
     * @var string
     *
     * @Column(name="ACC_PWD", type="string", length=255, nullable=false)
     */
    private $accessPassword;
    /**
     * @var string
     *
     * @Column(name="ACC_PWD_ALGRTHM", type="string", length=20, nullable=false)
     */
    private $accPwdHashAlgorithm = 'sha512';
    /**
     * @var string
     *
     * @Column(name="ACC_PWD_SALT", type="string", length=25, nullable=false)
     */
    private $accPwdSalt;

    /**
     * @var string
     *
     * @Column(name="EMAIL_ADDR", type="string", length=254, nullable=false)
     */
    private $emailAddr;
    /**
     * @var integer
     * @Column(name="STATE", type="integer", nullable=false)
     */
    private $state;
    /**
     * @var EventLog[]
     *
     * @OneToMany(targetEntity="\ifc\zend\modules\access\data\EventLog", cascade={"PERSIST", "MERGE", "REFRESH", "DETACH"},
     *  orphanRemoval=false, mappedBy="user")
     */
    private $loggedEvents;

    /**
     * Flag that determine if the user access has been authenticated
     * @var boolean
     */
    private $accessAuth = false;


    public function __construct() {
        $this->state = User::INACTIVE_STATE;
    }

    /**
     *
     * @return type
     */
    public function getId() {
        return $this->id;
    }

    public function getUserName() {
        return $this->userName;
    }

    public function setUserName($userName) {
        $this->userName = $userName;
    }

    public function getAccessPasswordSalt() {
        return $this->accPwdSalt;
    }

    public function setAccessPasswordSalt($accessPwdSalt) {
        $this->accPwdSalt = $accessPwdSalt;
    }

    public function setAccessPassword($plainPassword) {

        $hashedPassword = hash($this->accPwdHashAlgorithm, $plainPassword, false);
        $this->accessPassword = $hashedPassword;
    }


    public function getEmailAddr() {
        return $this->emailAddr;
    }

    public function setEmailAddr($emailAddr) {
        /**
         * @todo Implement email address checker
         */
        $this->emailAddr = $emailAddr;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function isStateInactive() {
        return (($this->state & User::INACTIVE_STATE) != 0) ? true : false;
    }

    public function isStateBlocked() {
        return (($this->state & User::BLOCKED_STATE) != 0) ? true : false;
    }

    public function isStateRegistredNotConfirmed() {
        return (($this->state & User::REGNOTCONFIRMED_STATE) != 0) ? true : false;
    }

    public function isStateActive() {
        return (($this->state & User::ACTIVE_STATE) != 0) ? true : false;
    }

    public function isStateForceChangeAccessPassword() {
        return (($this->state & User::FORCECHGACCPWD_STATE) != 0) ? true : false;
    }

    public function isAccessAuthenticated() {
        return $this->accessAuth;
    }


    public function addEventLog(EventLog $eventLog) {
        $this->loggedEvents->add($eventLog);
    }

    public function validateAccess($plainPassword) {

        if ($this->isStateActive()) {

            $hashedPassword = hash($this->accPwdHashAlgorithm, $plainPassword, false);

            switch (strcmp($this->accessPassword, $hashedPassword)) {
                case 0:
                    $this->accessAuth = true;
                    break;
                default:
                    $this->accessAuth = false;
            }
        } else {
            $this->accessAuth = false;
        }


        return $this->accessAuth;
    }

}
