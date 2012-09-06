<?php

/**
 * Description of SiteController
 *
 * @author Ivan Faixedes
 */
class SiteController extends Zend_Controller_Action
{
    public function homeAction()  {

        // Getting Bootstrap instance
        //$bootstrap = $this->getFrontController()->getParam('bootstrap');

        // Three manners of how to get Navigation Helper instance
        //$navigation = $bootstrap->getResource('navigation');
        //$navigationHelper = $this->view->getHelper('navigation');
        //$navigationHelperMenu = $this->view->getHelper('Zend_View_Helper_Navigation_Menu');

        /* LOCALE */
        //$translator = $bootstrap->getResource('translate');
        //$defLocale = Zend_Locale::getDefault();

        // Getting base URL helper
        //$base = $view->getHelper('baseUrl');
    }

}
