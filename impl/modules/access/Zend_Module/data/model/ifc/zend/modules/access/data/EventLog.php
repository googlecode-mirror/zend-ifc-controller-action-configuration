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
 * EventLog
 *
 * @Entity
 * @Table(name="IFCMACC_EVENT_LOG")

 */
class EventLog {

    /**
     * @var integer
     *
     * @Column(name="ID", type="integer", nullable=false, unique=true)
     * @GeneratedValue(strategy="AUTO")
     * @Id
     */
    private $id;
    /**
     * @var integer
     * @Column(name="EVT_TYPE", type="integer", nullable=false)
     */
    private $eventType;
    /**
     * @var integer
     * @Column(name="EVT_CODE", type="integer", nullable=false)
     */
    private $eventCode;
    /**
     * @var string
     *
     * @Column(name="EVT_NAME", type="string", length=65, nullable=false)
     */
    private $eventName;
    /**
     * @var string
     *
     * @Column(name="EVT_INFO", type="string", length=125, nullable=true)
     */
    private $eventInfo;
    /**
     * @var string
     *
     * @Column(name="IP_ADDR", type="string", length=39, nullable=true)
     */
    private $ipAddress;
    /**
     * @var datetime
     *
     * @Column(name="TMSTD", type="datetime", nullable=false)
     */
    private $timestamp;
    /**
     * @var integer
     *
     * @Column(name="TMSTD_MCSEC", type="integer", nullable=false)
     */
    private $tsMicroseconds = 0;
    /**
     * @var User
     *
     * @ManyToOne(targetEntity="\ifc\zend\modules\access\data\User", cascade={"ALL"}, fetch="LAZY", inversedBy="loggedEvents")
     * @JoinColumn(name="USERS_ID", referencedColumnName="ID", nullable=true,
     *    onDelete="CASCADE", onUpdate="CASCADE")
     */
    private $user;
    /**
     * @var EventLog
     *
     * @ManyToOne(targetEntity="\ifc\zend\modules\access\data\EventLog", cascade={"ALL"}, fetch="LAZY", inversedBy="childEvents")
     * @JoinColumn(name="EVENT_LOG_ID", referencedColumnName="ID", nullable=true,
     *    onDelete="CASCADE", onUpdate="CASCADE")
     */
    private $parentEvent;
    /**
     * @var EventLog[]
     *
     * @OneToMany(targetEntity="\ifc\zend\modules\access\data\EventLog", cascade={"PERSIST", "MERGE", "REFRESH", "DETACH"},
     *  orphanRemoval=false, mappedBy="parentEvent")
     */
    private $childEvents;

    public function __construct() {
        $this->timestamp = new \DateTime();
    }

    public function getId() {
        return $this->id;
    }

    public function getEventType() {
        return $this->eventType;
    }

    public function setEventType($eventType) {
        $this->eventType = $eventType;
    }

    public function getEventCode() {
        return $this->eventCode;
    }

    public function setEventCode($eventCode) {
        $this->eventCode = $eventCode;
    }

    public function getEventName() {
        return $this->eventName;
    }

    public function setEventName($eventName) {
        $this->eventName = $eventName;
    }

    public function getEventInfo() {
        return $this->eventInfo;
    }

    public function setEventInfo($eventInfo) {
        $this->eventInfo = $eventInfo;
    }

    public function getIpAddress() {
        return $this->ipAddress;
    }

    public function setIpAddress($ipAddress) {
        $this->ipAddress = $ipAddress;
    }

    public function getTimestamp() {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTime $timestamp = null) {
        if ($timestamp == null) {
            $this->timestamp = new \DateTime();
        } else {
            $this->timestamp = clone $timestamp;
        }
    }

    public function getTsMicroseconds() {
        return $this->tsMicroseconds;
    }

    public function setTsMicroseconds($tsMicroseconds) {
        $this->tsMicroseconds = $tsMicroseconds;
    }

    public function setPreciseCurrentimestamp() {

        $dateObj = new \DateTime();
        list($usec, $sec) = explode(' ', microtime());

        //Put timestamp
        $this->timestamp = $dateObj->setTimestamp($sec);

        if ($this->timestamp === FALSE) {
            throw new Exception("Error in getting current timestamp");
        }

        list($sec, $usec) = explode('.', $usec);

        //Put microseconds
        $this->tsMicroseconds = $usec;
    }

    public function getUser() {
        return $this->user;
    }

    public function setUser($owner) {
        $this->user = $owner;
    }

    public function getParentEvent() {
        return $this->parentEvent;
    }

    public function setParentEvent($parentEvent) {
        $this->parentEvent = $parentEvent;
    }

    public function getChildEvents() {
        return $this->childEvents;
    }

    public function setChildEvents($childEvents) {
        $this->childEvents = $childEvents;
    }

}

