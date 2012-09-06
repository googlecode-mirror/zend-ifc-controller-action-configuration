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

namespace ifc\zend\controller\action\configuration\view\helper;

/**
 * HeadMeta
 */
class HeadMeta extends HelperAbstract {

    /**
     * @var Zend_View_Helper_HeadMeta
     */
    protected $_headMeta;

    /**
     * Strategy pattern: Initialize the configuration.
     *
     * Change the meta tags used in the web page according the configuration
     * pararmeters
     *
     * See {@link \Zend_View}
     * See {@link \Zend_View_Helper_HeadMeta}
     *
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function init(\Zend_Controller_Request_Abstract $request) {

        $this->_headMeta = $this->_getView()->headMeta();
        $placement = isset($this->_options['placement']) ?
                $this->parsePlacement($this->_options['placement']) :
                'append';

        unset($this->_options['placement']);

        foreach ($this->_options as $metaValue => $defs) {
            $this->_headMeta->$placement($this->createMetaTag($metaValue, $defs));
        }
    }

    public function getHeadMeta() {
        return $this->_headMeta;
    }

    /**
     * Create the meta tag with the specified value and definitions atributes
     *
     * @param string $metaValue
     * @param array $metaDef
     * @return stdCalss With the meta tag
     */
    public function createMetaTag($metaValue, array $metaDef) {

        if (!isset($metaDef['content'])) {
            $metaDef['content'] = null;
        }

        if (!isset($metaDef['modifiers'])) {
            $metaDef['modifiers'] = array();
        }

        return $this->_headMeta->createData($metaDef['type'], $metaValue, $metaDef['content'], $metaDef['modifiers']);
    }

}

