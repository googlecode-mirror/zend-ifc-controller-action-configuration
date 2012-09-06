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
 * HeadLink
 */
class HeadLink extends HelperAbstract {

    /**
     * @var Zend_View_Helper_LinkMeta
     */
    protected $_headLink = null;

    public function init() {
        return $this->getHeadLink();
    }

    public function getHeadLink() {

        if ($this->_headLink === null) {

            $this->_headLink = $this->_getView()->headLink();
            $headLinksDefs = $this->getOptions();

            foreach ($headLinksDefs as $headLinkId => $options) {

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

        return $this->_headLink;
    }

}
