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


class Test01_LoginController extends \Zend_Controller_Action {

    public function simpleLoginAction() {
        /*
        $this->_helper->layout()
        ->setLayout('dojo/layouts/site-dojo-helper-programatic');
        */

        $this->_helper->layout()
        ->setLayout('layouts/site-dojo');


        $this->view->dojo()
        ->enable()
        ->setDjConfigOption('parseOnLoad', true)
        ->setDjConfigOption('locale', 'es-ES')
        ->setDjConfigOption('isDebug', true)
        ->setLocalPath('scripts/dojo/1.6/dojo/dojo.js')
        ->addStyleSheetModule('dijit.themes.soria');

        $loginForms = \Zend_Registry::get('LoginForms');
        $form = $loginForms->getForm('login');

        $this->view->form = $form;
        //return $this->render('dojo-form');

    }

    public function ajaxLoginAction() {
        /*
        $this->_helper->layout()
        ->setLayout('dojo/layouts/site-dojo-helper-programatic');
*/


        $this->_helper->layout()
        ->setLayout('layouts/site-dojo');

         $this->view->dojo()
        ->enable()
        ->setDjConfigOption('parseOnLoad', true)
        ->setDjConfigOption('locale', 'es-ES')
        ->setDjConfigOption('isDebug', true)
        ->setLocalPath('scripts/dojo/1.6/dojo/dojo.js')
        ->addStyleSheetModule('dijit.themes.soria');


        $loginForms = \Zend_Registry::get('LoginForms');
        $form = $loginForms->getForm('login');


        /* Manual configuration
        $form = new \Zend_Dojo_Form();
        $form->setAttrib('id', 'access.validation')
        ->setAction('access/login/in')
        ->setMethod('post')
        ->setName('access.validation')
        ->setElementFilters(array('StringTrim'));
        //Zend_Dojo::enableForm($form);

        $form->addElement(
                'ValidationTextBox', 'username', array('required' => 'true',
                        'regExp' => '^([a-zA-Z])(([a-zA-Z0-9]){5,29})$'
                )
        );
        $form->addElement('Button', 'login');
        */



        $this->view->form = $form;
//        return $this->render('dojo-ajax-form');
    }

}
