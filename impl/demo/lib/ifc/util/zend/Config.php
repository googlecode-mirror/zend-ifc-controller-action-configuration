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

namespace ifc\util\zend;

require_once 'ifc/zend/controller/action/configuration/ZendAbstract.php';

/**
 * Class to include utilities related to the classes of namespace Zend_Config
 */
class Config {

    /**
     * Create a concrete Zend_Config from the specified configuration file.
     * The Zend_Config type is induced by the extension file; the supported
     * file types are: .ini, .xml, .yml, .json
     *
     * See {@link \Zend_Config}
     * See {@link \Zend_Config_Ini}
     * See {@link \Zend_Config_Xml}
     * See {@link \Zend_Config_Yaml}
     * See {@link \Zend_Config_Json}
     *
     * @param string $filePath The path to the file to load
     * @param mixed  $section The type and the values depends of the file type
     *      to load @see the {@link Zend_Config} subclasses
     * @param mixed $options The type and the values depends of the file type
     *      to load @see the {@link Zend_Config} subclasses
     * @return \Zend_Config The concrete Zend_Config file associated to the file type
     * @throws \ifc\util\Exception if fileSpec param is null or if the file
     *      extension is not recognized
     */
    public static function loadConfigFile($filePath, $section = null, $options = null) {

        if (!isset($filePath)) {
            require_once '\ifc\Util\Exception.php';
            throw new Exception('The path to the file to load cannot be NULL');
        }

        $suffix = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        switch ($suffix) {
            case 'ini':
                $config = new \Zend_Config_Ini($filePath, $section, $options);
                break;

            case 'xml':
                $config = new \Zend_Config_Xml($filePath, $section, $options);
                break;
            case 'yml':
                $config = new \Zend_Config_Yaml($filePath, $section, $options);
                break;
            case 'json':
                $config = new \Zend_Config_Json($filePath, $section, $options);
                break;
            default:
                require_once '\ifc\Util\Exception.php';
                throw new Exception('Invalid configuration file provided; unknown extension file');
        }

        return $config;
    }

    /**
     * @todo comment this method
     * @param sring $basePath
     * @param type $relativePath
     * @return type
     */
    public static function prepareAbsolutePath($basePath, $relativePath) {

        $basePath = (isset($basePath)) ? rtrim($basePath, '/\\') : '';
        $relativePath = (isset($relativePath)) ? ltrim($relativePath, '/\\') : '';

        return $basePath . DIRECTORY_SEPARATOR . $relativePath;

    }

}

