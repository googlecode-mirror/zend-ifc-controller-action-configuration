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
 * HelperAbstract
 */
abstract class HelperAbstract extends \Zend_Application_Resource_ResourceAbstract {

    private static $_view = null;

    public function init() {

        if (HelperAbstract::$_view === null) {
            $this->getBootstrap()->bootstrap('view');
            HelperAbstract::$_view = $this->getBootstrap()->view;
        }
    }

    protected function _getView() {
        return HelperAbstract::$_view;
    }


    /**
     * Parse the placement method avaliable in Zend_View_Helper_Placeholder_Container_Abstract
     * and return this in upper o lower case or if the placement value is not
     * identified as Zend_View_Helper_Placeholder_Container_Abstract constants
     * return the integer conversion to use as offset index
     *
     * See {@link \Zend_View_Helper_Placeholder_Container_Abstract}
     *
     * @param string $placement
     *  The value can be a Zend_View_Helper_Placeholder_Container_Abstract constants
     *  ('SET' to overwrites all previously stored values, 'APPEND' to add at the
     *  end of stack, or 'PREPEND' to add at the top of stack) or and offset index.
     *
     *  If the value is not a Zend_View_Helper_Placerholder_Container_Abstract contant
     *  then the method return the value returned by inval() PHP function
     *
     * @param bool $toLowerCase
     *  If it is true, the method returns the $placement in upper case otherwise
     *  in lower case
     *  This rule only is applied if the $placement values is a constant of
     *  Zend_View_Helper_Placeholder_Container_Abstract (case-insensitve comparison)
     *
     * @return mixed string|int The method name of the
     *  Zend_View_Helper_Placeholder_Container_Abstract to use or offset index
     */
    public function parsePlacement($placement, $toUpperCase = false) {
            if (strcasecmp('SET', $placement) == 0) {
                return ($toUpperCase) ? 'SET' : 'set';

            } else  if (strcasecmp('APPEND', $placement) == 0) {
                return ($toUpperCase) ? 'APPEND' : 'append';

            } else  if (strcasecmp('PREPEND', $placement) == 0) {
                return ($toUpperCase) ? 'PREPEND' : 'prepend';

            } else {
                return intval($placement);
            }

    }

}

