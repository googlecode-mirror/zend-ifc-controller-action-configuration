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
 * HeadTitle
 */
class HeadTitle extends HelperAbstract {

    /**
     * @var Zend_View_Helper_HeadTitle
     */
    protected $_headTitle;

    protected $_type = "APPEND";

    protected $_separator = "|";

    protected $_translate = true;


    public function init() {
        return $this->getHeadTitle();
    }

    public function getHeadTitle() {

        if ($this->_headTitle === null) {
            parent::init();

            $options = $this->getOptions();
            $this->_headTitle = $this->_getView()->headTitle($options['title'], $this->_type);
            $this->_headTitle->setSeparator($this->_separator);

            if ($this->_translate) {
                $this->_headTitle->enableTranslation();
            } else {
                $this->_headTitle->disableTranslation();
            }

        }

        return $this->_headTitle;
    }

    /**
     * Set the type of chaining of the titles
     *
     * @param string $type
     */
    public function setType($type) {
        $this->_type = $type;
    }

    /**
     * Set the string of separation between the chains titles
     *
     * @param string $separator
     */
    public function setSeparator($separator) {
        $this->_separator = $separator;
    }

    /**
     * Enable or unable tilte translation
     *
     * @param bool $flag
     */
    public function setTranslate($flag) {
        $this->_translate = $flag;
    }

}

