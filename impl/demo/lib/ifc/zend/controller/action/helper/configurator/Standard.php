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

namespace ifc\zend\controller\action\helper\configurator;

/**
 * Standard
 */
class Standard extends \Zend_Controller_Action_Helper_Abstract {

    /**
     * @var \Zend_Application_Bootstrap_BootstrapAbstract
     *      The reference to the Bootstrap object of the application
     */
    protected $_bootstrap;

    public function init() {
        $this->_bootstrap = $this->getActionController()
                ->getInvokeArg('bootstrap');
    }

    /**
     * Retrieve name that identify this helper class
     *
     * @return string
     */
    public function getName() {
        $name = parent::getName();
        return 'Configurator_' . $name;
    }

    /**
     * Register and run the the defined configurations of the requested module.
     *
     * For more information of when this method is called
     * @see \Zend_Controller_Action_Helper_Abstract
     * {@link \Zend_Controller_Action_Helper_Abstract::preDispatch}
     */
    public function preDispatch() {

        $moduleName = $this->getRequest()->getModuleName();

        $moduleConfigOpt = $this->_bootstrap->getResource('ifc\zend\application\resource\ModuleConfigurator');
        $configurator = $moduleConfigOpt->getConfigurator($moduleName);



        $moduleConfigs = $this->_loadModuleConfigurationsFile(
                $moduleConfigOpt->getConfigFileDirectory($moduleName),
                $moduleConfigOpt->getConfigFileName($moduleName),
                $moduleConfigOpt->getConfigFileSection($moduleName));

        if (isset($moduleConfigs)) {
            $configurator->registerConfigurations($moduleConfigs['configurations']);
            $configurator->runAll($this->getRequest());
        }
    }

    /**
     * Load the configuration file that define the configurations of the module
     * and return the content into an array.
     * INI is the expected file format.
     *
     * @param string $relativePath Relative path from module root to the directory
     *                             where the configuraton file is locatedfrom module
     *                             root directory
     * @param string $fileName The filename of the configuration file
     * @param string $section  The section of the file to load
     * @return null|array
     */
    protected function _loadModuleConfigurationsFile($relativePath, $fileName,
            $section) {
        $moduleDir = $this->getFrontController()->getModuleDirectory(
                $this->getRequest()->getModuleName());
        try {
            $configFile = new \Zend_Config_Ini($moduleDir . DIRECTORY_SEPARATOR .
                            $relativePath . DIRECTORY_SEPARATOR . $fileName, $section);
        } catch (\Zend_Config_Exception $e) {
            if ($this->_bootstrap->hasResource('log')) {
                $logger = $this->_bootstrap->getResource('log');
                $logger->notice('Imposible to load module configuration file ; exception message: ' .
                        $e->getMessage());
            }

            return null;
        }

        return (count($configFile) == 0) ? null : $configFile->toArray();
    }

}

