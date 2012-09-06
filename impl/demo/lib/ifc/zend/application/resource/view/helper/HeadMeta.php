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

namespace ifc\zend\application\resource\view\helper;

/**
 * HeadMeta
 */
class HeadMeta extends HelperAbstract {

    /**
     * @var Zend_View_Helper_HeadMeta
     */
    protected $_headMeta;

    public function init() {
        return $this->getHeadMeta();
    }

    public function getHeadMeta() {

        if ($this->_headMeta === null) {

            $this->_headMeta = $this->_getView()->headMeta();
            $options = $this->getOptions();


            if (isset($options['placement'])) {
                $placement = $this->parsePlacement($options['placement']);

                if (is_int($placement)) {
                    require_once 'Exception.php';
                    throw new Exception('The offset (set element in a concrete index
                            into stack) method of the Placeholder HeadMeta is not
                            supporte by this resource');
                }
            }
        } else {
            $placement = 'append';
        }

        unset($options['placement']);

        foreach ($options as $metaValue => $defs) {
            $this->_headMeta->$placement($this->createMetaTag($metaValue, $defs));
        }

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

