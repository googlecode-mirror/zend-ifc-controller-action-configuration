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
 * SiteConfiguratorLocation
 */
class SiteConfiguratorLocation extends \Zend_Application_Resource_ResourceAbstract {

    public function init() {

        if (!isset($this->_locale)) {
    //        parent::getLocale();
        }

        //$language = $this->_locale->getLanguage();

        //@todo Implement if root locale has been applieed; if this is applied then
        // change the root locale to one locale by default to avoid the application
        // break in some situations for example en currency usage.
        //@see http://files.zend.com/help/Zend-Framework/zend.locale.html --> example 29.1

    }

    protected function _getLocale() {

        //if (Zend_Reg)
    }

}
