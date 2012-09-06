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
 * ModuleConfigurator
 */
class ModuleConfigurator extends \Zend_Application_Resource_ResourceAbstract {

    /**
     * @var string Relative default directory path from module root where the
     *             configuration file is located
     */
    protected $_defConfigFileDir;
    /**
     * @var string Default filename of the module configuration file
     */
    protected $_defConfigFileName;
    /**
     * @var string Default section of the module configuration file
     */
    protected $_defConfigFileSection;
    /**
     * @var string Class name of the default configurator to use
     *             The configurator class has to implments the
     *             {@link \ifc\zend\controller\action\ConfiguratorInterface}
     */
    protected $_defClassName;
    /**
     * @var string Path to the defined default configurator class
     *             @see {_defConfiguratorClassName} attribute
     */
    protected $_defClassPath;
    /**
     * @var array Array to define concrete options for the configurator of each module
     *            Accepted  per module:
     *              - configDir
     *              - configFile
     *              - className
     *              - classPath
     */
    protected $_modulesOptions;
    /**
     * @var {@link \ifc\zend\controller\action\ConfiguratorInterface}
     *
     *      The default configuartor that already have been instantiated.
     *      Note: For a bit optimization
     */
    protected $_defConfigurator;
    /**
     * @var {@link \ifc\zend\controller\action\ConfiguratorInterface}
     *
     *      The configuartors that already have been instantiated.
     *      Note: For a bit optimization
     */
    protected $_configurators;
    /**
     * @var array Paths to the class definition files of general action helpers
     */
    protected $_actionHelpers = null;

    /**
     * Initilalize the Module Configurator
     *
     * @return ModuleConfigurator
     */
    public function init() {

        $bootstrap = $this->getBootstrap();
        $bootstrap->bootstrap('frontController');

        $frontController = $bootstrap->getResource('frontController');

        if (!$frontController->hasPlugin('\ifc\zend\controller\plugin\module\ActionHelper')) {
            require_once 'ifc/zend/controller/plugin/module/ActionHelper.php';
            $frontController->registerPlugin(new \ifc\zend\controller\plugin\module\ActionHelper());
        }

        // This resource is used later to create the configuration of each module
        return $this;
    }

    /**
     * Set the options need to locate and load the module configuration file.
     * If there is not specified the module, then the configuration file is general,
     * to all modules that no specify this, otherwise, the configuration file
     * correspond to the module.
     *
     * @param array $configFileOp
     * @param string $moduleName
     * @throws Exception
     */
    public function setConfigFile(array $configFileOp, $moduleName = null) {

        $optCounter = 0;

        foreach ($configFileOp as $option => $value) {
            switch (strtolower($option)) {
                case 'directory':
                    $this->_setConfigFileDirectory($value, $moduleName);
                    $optCounter++;
                    break;
                case 'name':
                    $this->_setConfigFileName($value, $moduleName);
                    $optCounter++;
                    break;
                case 'section':
                    $this->_setConfigFileSection($value, $moduleName);
                    $optCounter++;
                    break;
                default:
                    $this->setConfigFile($value, $option);
            }
        }

        if ($optCounter < 3) {
            require_once 'Exception.php';
            throw new Exception('Missing configuration file options; the options are:
                    directory, name, section');
        }
    }

    /**
     * Set the directory where the module locate the configuration file.
     * If there is not a specification of the module the directory is set by
     * default, otherwise to concrete module.
     *
     * @param string $path
     * @param string $moduleName
     */
    protected function _setConfigFileDirectory($path, $moduleName = null) {
        if ($moduleName === null) {
            $this->_defConfigFileDir = $path;
        } else {
            $this->_modulesOptions[$moduleName]['configfile']['directory'] = $path;
        }
    }

    /**
     * Retrieve the relative path to directory where the module configuration
     * file is located.
     * If there is not a specification of the module or not exist this option
     * in specified module, then the default configuration path directory  is
     * returned, otherwise the configuration path directory of the module
     *
     * @param string $moduleName
     * @return string
     */
    public function getConfigFileDirectory($moduleName = null) {

        if (isset($this->_modulesOptions[$moduleName]['configfile']['directory'])) {
            return $this->_modulesOptions[$moduleName]['configsfile']['directory'];
        } else {
            return $this->_defConfigFileDir;
        }
    }

    /**
     * Set the filename where the module specifies its configuartions and parameters.
     * If there is not a specification of the module the filename is set by
     * default, otherwise to concrete module.
     *
     * @param string $fileName
     * @param string $moduleName
     */
    protected function _setConfigFileName($fileName, $moduleName = null) {
        if ($moduleName === null) {
            $this->_defConfigFileName = $fileName;
        } else {
            $this->_modulesOptions[$moduleName]['configfile']['name'] = $fileName;
        }
    }

    /**
     * Retrieve the configuraiton filename where the module configurations
     * are defined.
     * If there is not a specification of the module or not exist this option
     * in specified module, then the default configuration filename  is
     * returned, otherwise the configuration filename of the module
     *
     * @param string $moduleName
     * @return string
     */
    public function getConfigFileName($moduleName = null) {

        if (isset($this->_modulesOptions[$moduleName]['configfile']['name'])) {
            return $this->_modulesOptions[$moduleName]['configfile']['name'];
        } else {
            return $this->_defConfigFileName;
        }
    }

    /**
     * Set the section of the module configuration file.
     * If there is not a specification of the module the section is set to the
     * default module configuration file, otherwise to concrete module.
     *
     * @param string $fileName
     * @param string $moduleName
     */
    protected function _setConfigFileSection($section, $moduleName = null) {
        if ($moduleName === null) {
            $this->_defConfigFileSection = $section;
        } else {
            $this->_modulesOptions[$moduleName]['configfile']['section'] = $section;
        }
    }

    /**
     * Retrieve the section module configuraiton file.
     * If there is not a specification of the module or not exist this option
     * in specified module, then the returned section pertains to default module
     * configuration file, otherwise the section of the configuration file
     * of the module
     *
     * @param string $moduleName
     * @return string
     */
    public function getConfigFileSection($moduleName = null) {

        if (isset($this->_modulesOptions[$moduleName]['configfile']['section'])) {
            return $this->_modulesOptions[$moduleName]['configfile']['section'];
        } else {
            return $this->_defConfigFileSection;
        }
    }

    /**
     * Set the configurator parameters.
     * If there is not a specification of the module the parameters are assigned
     * to default configurator, otherwise to the concrete module configurator.
     *
     * @param array $configuratorOptions
     * @param string $moduleName
     */
    public function setConfigurator(array $configuratorOptions, $moduleName = null) {

        if ($moduleName === null) {
            if (isset($configuratorOptions['class'])) {
                $this->_defClassName = $configuratorOptions['class'];
            } else {
                require_once 'Exception.php';
                throw new Exception('The class options is needed for define the
                        default class name that implements the default
                        \ifc\zend\controller\action\ConfiguratorInterface');
            }

            if (isset($configuratorOptions['path'])) {
                $this->_defClassPath = $configuratorOptions['path'];
            } else {
                $this->_defClassPath = null;
            }
        } else {
            if (isset($configuratorOptions['class'])) {
                $this->_modulesOptions[$moduleName]['class'] = $configuratorOptions['class'];
            } else {
                require_once 'Exception.php';
                throw new Exception('The class options is needed
                        for define the default class name that implements the
                        \ifc\zend\controller\action\ConfiguratorInterface');
            }

            if (isset($configuratorOptions['path'])) {
                $this->_modulesOptions[$moduleName]['path'] = $configuratorOptions['path'];
            }
            /* The parameter is not required
            else {
                require_once 'Exception.php';
                throw new Exception('The path options is needed
                    for define the path to the default class that implements the
                    \ifc\zend\controller\action\ConfiguratorInterface');
            }
             *
             */
        }
    }

    /**
     * Retrieve an instance of a configurator object.
     * If this already has been created before, return this, else trying
     * to create one from the options of the module, otherwise from the
     * default configurator class.
     *
     * @param string $moduleName
     * @return \ifc\zend\controller\action\ConfiguratorInterface
     * @throws Exception If the configuration cannot be created or the class doesn't
     *          implements the \ifc\zend\controller\action\ConfiguratorInterface
     */
    public function getConfigurator($moduleName = null) {

        if (isset($this->_configurators[$moduleName])) {
            return $this->_configurators[$moduleName];
        }

        if (isset($this->_modulesOptions[$moduleName]['configurator'])) {

            $className = $this->_modulesOptions[$moduleName]['configurator']['class'];
            $path = isset($this->_modulesOptions[$moduleName]['configurator']['path']) ?
                    $this->_modulesOptions[$moduleName]['configurator']['path'] : null;
        } else {
            $className = $this->_defClassName;
            $path = $this->_defClassPath;
        }

        $module = $this->_createConfigurator($className, $path);

        if ($module === null) {
            require_once 'Exception.php';
            throw new Exception('Impossible create object from class: ' .
                    $className . ' (' . $path . ')');
        } else {
            if ($module instanceof \ifc\zend\controller\action\configurator\ConfiguratorInterface) {

                if ($moduleName !== null) {
                    $this->_configurators[$moduleName] = $module;
                }

                return $module;
            } else {
                require_once 'Exception.php';
                throw new Exception('Indicated configuration class not implments
                    \ifc\zend\controller\action\ConfiguratorInterface');
            }
        }
    }

    /**
     * Set the paths to the class definition files of the action helpers
     * to register their.
     * If there is not a specification of the module the class paths refer to
     * the general action helpers, otherwise to the concrete module action helpers
     *
     * @param array $actionHelpers
     * @param string $moduleName
     */
    public function setActionHelpers(array $actionHelpers, $moduleName = null) {
        if ($moduleName === null) {
            $this->_actionHelpers = $actionHelpers;
        } else {
            $this->_modulesOptions[$module]['actionhelpers'] = $actionHelpers;
        }
    }

    /**
     * Retrieve the paths to the class definition files of the action helpers.
     * If there is not a specification of the module the class paths to general
     * action helpers are returned, else returns the class paths from general
     * and concrete module action helpers, otherwise null
     *
     * @param string $moduleName
     * @return array
     */
    public function getActionHelpers($moduleName = null) {

        if (isset($this->_modulesOptions[$moduleName]['actionHelpers'])) {
            $actionHelpers = $this->_modulesOptions[$moduleName]['actionHelpers'];

            return ($this->_actionHelpers === null) ? $actionHelpers :
                    array_merge($this->_actionHelpers, $actionHelpers);
        } else {
            return $this->_actionHelpers;
        }
    }

    /**
     * Set the configuration options of the specified modules.
     * Module options are the same that the general module configurator options
     *
     * @param array $moduleOptions
     * @throws Exception When there are some unreconigzed options
     */
    public function setModules(array $moduleOptions) {

        foreach ($moduleOptions as $moduleName => $options) {
            foreach ($options as $option => $value) {
                switch (strtolower($option)) {
                    case 'configfile':
                        $this->setConfigFile($value, $moduleName);
                        break;
                    case 'configurator':
                        $this->setConfigurator($value, $moduleName);
                        break;
                    case 'actionhelpers':
                        $this->setActionHelpers($value, $moduleName);
                        break;
                    /*Eliminar
                    case 'configurationspaths':
                        $this->setConfigurationsPaths($value, $moduleName);
                        break;
                     *
                     */
                    default:
                        require_once 'Exception.php';
                        throw new Exception('Unrecognized module option: ' . $option);
                }
            }
        }
    }

    /**
     * Create an object from class defined in path
     *
     * @param string $className
     * @param string $path
     * @return object
     */
    protected function _createConfigurator($className, $path) {

        //Class already located
        if (class_exists($className)) {
            return new $className();
        }

        //Class name is with its complete namespace
        $posFileName = strpos($className, '\\');

        if ($posFileName !== false) {
            $classNamePath = str_replace('\\', DIRECTORY_SEPARATOR, $className);
            $classNamePath = ltrim($classNamePath, '/\\');

            if (($path !== null) && ($path !== '')) {
                $fileName = $path . '/' . $classNamePath . '.php';
            } else {
                $fileName = $classNamePath . '.php';
            }

            require_once $fileName;
            return new $className;
        }



        //Class name is equal at file name
        $path = rtrim($path, '/\\') . '/';
        $fileName = $path . $className . '.php';

        if (file_exists($fileName)) {
            include_once $fileName;
            return new $className();
        }

        //Filename is part of the complete class name (path separation class name used into ZF)
        $posFileName = strrpos($className, '_');

        if ($posFileName === false) {
            return null;
        }

        $fileName = substr($className, $posFileName + 1);
        $fileName = $path . $fileName . '.php';

        if (file_exists($fileName)) {
            include_once $fileName;
            return new $className();
        }

        //Path only contains the part of the path not represented by the class name
        //(path separation class name used into ZF)
        $classNamePath = str_replace('_', DIRECTORY_SEPARATOR, substr($className, 0, $posFileName - 1));
        $fileName = $path . $classNamePath . $fileName . '.php';

        if (file_exists($fileName)) {
            include_once $fileName;
            return new $className();
        }

        return null;
    }

}