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

namespace ifc\zend\application\resource;

/**
 * NavigationFileLoader
 */
class NavigationFileLoader extends \Zend_Application_Resource_ResourceAbstract {

    /**
     * @var array The options of file that contains the pages of the
     *            navigation structure
     */
    protected $_file;


    public function init() {


        $bootstrap = $this->getBootstrap();

        if ($bootstrap->hasPluginResource('navigation')) {
            $bootstrap('navigation');
            $bootstrap->navigation->addPages($this->_loadPagesFromConfigFile());
            return;
        } else {
            $bootstrap->registerPluginResource('navigation');
            $navPluginResource = $bootstrap->getPluginResource('navigation');
        }


        $newoptions = $this->_prepareNavigationResourceOptions($navPluginResource->getOptions());
        $navPluginResource->setOptions($newoptions);

        $bootstrap->registerPluginResource($navPluginResource);
        $bootstrap->bootstrap('navigation');
    }

    /**
     * Retrieve the pages navigation structure specified in the configuaration
     * file
     *
     * @return Zend_Config The navigation pages structure or null if the
     *                      configuration file has not been specified.
     * @throws ifc\zend\application\resource\Exception
     */
    protected function _loadPagesFromConfigFile() {
        if (isset($this->_file)) {

            $configFile = $this->_file['path'];
            $configSection = $this->_file['section'];

            $suffix = strtolower(pathinfo($configFile, PATHINFO_EXTENSION));

            switch ($suffix) {
                case 'ini':
                    $config = new \Zend_Config_Ini($configFile, $configSection);
                    break;

                case 'xml':
                    $config = new \Zend_Config_Xml($configFile, $configSection);
                    break;
                default:
                    require_once 'Exception.php';
                    throw new Exception('Invalid configuration file provided; unknown config type');
            }

            return $config;
        } else {
            return null;
        }
    }

    protected function _prepareNavigationResourceOptions($navigationOptions) {

        if (isset($this->_file)) {
            unset($this->_options['file']);
        } else {
            $this->setFile($this->_options);
        }

        $pagesFromConfigFile = $this->_loadPagesFromConfigFile()->toArray();

        //register the pages into the options array with the key used by navigation resource
        $this->_options['pages'] = $pagesFromConfigFile;

        return $this->mergeOptions($this->_options, $navigationOptions);
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

}