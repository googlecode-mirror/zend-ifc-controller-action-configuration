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

namespace ifc\zend\controller\action\configuration;

require_once 'ifc/zend/controller/action/configuration/ZendAbstract.php';
require_once 'ifc/util/zend/Config.php';

/**
 * Description
 */
class NavigationFileLoader extends ZendAbstract {

    /**
     * @var array The options of file that contains the page list of the
     *            navigation structure
     */
    protected $_file;
    /**
     * @var bool Flag that determine if the pages are complementary to
     *           the existent pages in the registered navigation structure or
     *           if these will be replaced.
     */
    protected $_complementaryPages = true;


    /**
     * Strategy pattern: Initialize the configuration.
     *
     * It adds pages from the configuration file to registered navigation container,
     * otherwise register a new one and it adds the pages to this.
     *
     * See {@link \Zend_Navigation_Container}
     * See {@link \Zend_Application_Bootstrap_BootstrapAbstract}
     * See {@link \Zend_Application_Resource_Navigation}
     * See getContainer() from {@link \Zend_View_Helper_Navigation_HelperAbstract}
     *
     * @param Zend_Controller_Request_Abstract $request Request to process (in this
     *      configuration is not used)
     */
    public function init(\Zend_Controller_Request_Abstract $request) {

        if ($this->_getBootstrap()->hasResource('navigation')) {

            $navigation = $this->_getBootstrap()->getResource('navigation');

        } else if (\Zend_Registry::isRegistered('Zend_Navigation') &&
                (\Zend_Registry::get('Zend_Navigation') instanceof \Zend_Navigation_Container)) {

            // This is the default container used by view if it didn't has a defined container
            $navigation = \Zend_Registry::get('Zend_Navigation');

        } else {
            // There is not registered \Zend_Navigation_Containter; we are going
            // to register one through the bootstrap
            $navigation = new \Zend_Navigation_Container();
            $this->_getBootstrap()->getContainer()->{'navigation'} = $navigation;
        }

        // If the configuraiton file defines a new navigation structure, then
        // replaces old structure
        if (!$this->_complementaryPages) {
            $navigation->removePages();
        }

        if (isset($this->_file)) {
            $navigation->addPages(\ifc\util\zend\Config::loadConfigFile(
                    $this->_file['path'], $this->_file['section']));
        }

    }

    public function setFile(array $fileOptions) {
        if (!isset($fileOptions['path'])) {
            require_once 'Exception.php';
            throw new Exception('It requires the \'path\' parameter with the full path to configuration file.');
        }

        if (!isset($fileOptions['section'])) {
            $this->_file['section'] = '';
        }

        $this->_file = $fileOptions;
    }

    public function setComplementaryPages($flag) {
        $this->_complementaryPages = $flag;
    }

}

