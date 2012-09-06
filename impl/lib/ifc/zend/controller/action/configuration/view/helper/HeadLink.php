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
 * HeadLink
 */
class HeadLink extends HelperAbstract {

    /**
     * @var Zend_View_Helper_LinkMeta
     */
    protected $_headLink = null;

    /**
     * Strategy pattern: Initialize the configuration.
     *
     * Change the head link elements of the web page according the configuration
     * pararmeters
     *
     * See {@link \Zend_View}
     * See {@link \Zend_View_Helper_HeadTitle}
     *
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function init(\Zend_Controller_Request_Abstract $request)  {
        $this->_headLink = $this->_getView()->headLink();

        foreach ($this->_options as $headLinkId => $options) {

            $placement = $this->parsePlacement($options['placement'], true);
            unset($options['placement']);

            if (!isset($options['rel'])) {
                require_once 'Exception.php';
                throw new Exception('The definition of a HEAD LINK element require at least
                            one minimum option parameter:\"rel\"');
            }

            if (is_int($placement)) {
                $this->_headLink->offsetSet($placement, $this->_headLink->createData($options));
            } else {
                $this->_headLink->headLink($options, $placement);
            }
        }
    }

    public function getHeadLink() {
        return $this->_headLink;
    }

}
