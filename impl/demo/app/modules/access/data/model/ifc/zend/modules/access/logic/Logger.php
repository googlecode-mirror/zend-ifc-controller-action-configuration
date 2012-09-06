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
 * Class to provide a set of common method to register EventLog into DB
 *
 * Implemented singleton software pattern
 */
class Logger {

    /**
     * Instance of this class to ensure the unique reference that can exists.
     * Singleton pattern premise.
     *
     * @var ifc\zend\modules\access\Logic\Logger
     */
    private static $_instance;

    /**
     * Entity manger for using to registry the EventLog objects
     *
     * @var \Doctrine\ORM\EntityManager
     */
    protected $_entityManager;

    /**
     * Default constructor
     *
     * Object class creation restriction
     * Singleton software pattern premise
     */
    private function __construct() {

    }

    /**
     * Get the unique instance that can be exist of this class.
     * Singleton software patter premise.
     *
     * @param \Doctrine\ORM\EntityManager $entityManager
     * @return type
     */
    public static function getInstance(\Doctrine\ORM\EntityManager $entityManager) {
        if (!isset($_instance)) {
            Logger::$_instance = new Logger();
        }

        Logger::$_instance->_entityManager = $entityManager;
        return Logger::$_instance;
    }

    /**
     * Register the EventLog from the builder
     *
     * @param EventLogBuilderInterface $eventLogBuilder The builder that create
     *  the EventLog
     */
    public function registerEventLog(EventLogBuilderInterface $eventLogBuilder) {

        // Get the event log to register
        $eventLog = $eventLogBuilder->getEventLog();
        $eventLog->setPreciseCurrentimestamp();

        // Register the event log into DB
        $this->_entityManager->persist($eventLog);
    }

    public function registerUserEventLog(EventLogBuilderInterface $eventLogBuilder,
            \ifc\zend\modules\access\data\User $user) {

        // Get the event log to register
        $eventLog = $eventLogBuilder->getEventLog();
        $eventLog->setPreciseCurrentimestamp();
        //$user->addEventLog($eventLog);
        $eventLog->setUser($user);

        // Register the event log into DB
        $this->_entityManager->persist($eventLog);
        //$this->_entityManager->persist($user);
    }

    /**
     *
     * @param \ifc\zend\modules\access\data\User $user
     * @return type
     */
    public function registerDeniedAccessByStateEventLog(\ifc\zend\modules\access\data\User $user) {

    }

}

