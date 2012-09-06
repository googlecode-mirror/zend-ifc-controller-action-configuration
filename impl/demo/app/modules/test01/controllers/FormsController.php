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

use ifc\zend\modules\test01\Configuration as Config;

class Test01_FormsController extends \Zend_Controller_Action {

	public function ctrlActionConfigAction() {
		$config = Config::getInstance();

		$dojoBodyCss = $config->getDojoBodyCSS();

		if (isset($dojoBodyCss)) {
			$this->view->dojoBodyCss = $dojoBodyCss;
		}

		$this->_helper->layout()
		->setLayout('layouts/site-dojo');

	}


	public function dojoIntroAction() {

	}

	public function dojoHelperAction() {
	    $this->_helper->layout()
	    ->setLayout('layouts/site-dojo');

	    $this->view->dojo()
		->enable()
		->setDjConfigOption('parseOnLoad', true)
		->setDjConfigOption('locale', 'en-US')
		->setDjConfigOption('isDebug', true)
		->setLocalPath('scripts/dojo/1.6/dojo/dojo.js')
		->addStyleSheetModule('dijit.themes.soria');


		$form = new Zend_Form();
		$form->setAttrib('id', 'mainform');
		$form->setAttrib('class', 'siteInteraction');
		$form->setAction('test/zend-forms/login')
		->setMethod('post');

		Zend_Dojo::enableForm($form);

		$form->addElement(
				'NumberTextBox', 'foo', array('type' => 'percent',
						'value' => '0.1',
						'label' => 'Percentage:'
				)
		);

		$form->addElement(
				'NumberTextBox', 'foodos', array('constraints' => array('min' => 0, 'max' => 15, 'places' => 0), 'required' => 'true',
						'value' => '11',
						'label' => 'Number text Box:',
						'class' => '')
		);

		$form->addElement('button', 'btn', array('label' => 'SEND'));
		//$form->getElement('btn')->removeDecorator('DtDdWrapper');


		$this->view->form = $form;
	}


	public function dojoFormZendConfigAction() {
	    $this->_helper->layout()
	    ->setLayout('layouts/site-dojo');

	    $this->view->dojo()
		->enable()
		->setDjConfigOption('parseOnLoad', true)
		->setDjConfigOption('locale', 'en-US')
		->setDjConfigOption('isDebug', true)
		->setLocalPath('scripts/dojo/1.6/dojo/dojo.js')
		->addStyleSheetModule('dijit.themes.soria');


		$testForms = \Zend_Registry::get('TestForms');
		$form = $testForms->getForm('form1');
		Zend_Dojo::enableForm($form);

		$testingElements = \Zend_Registry::get('TestFormElements');

		$textElement1 = $testingElements->getElement('texts.text1');
		$form->addElement($textElement1);

		$buttonElement = $testingElements->getElement('buttons.button1');
		$form->addElement($buttonElement);

		$this->view->form1 = $form;

		$form = $testForms->getForm('form2');
		Zend_Dojo::enableForm($form);

		$textElement2 = $testingElements->getElement('texts.text2');
		$form->addElement($textElement2);
		$buttonElement = $testingElements->getElement('buttons.button2');
		$form->addElement($buttonElement);

		$this->view->form2 = $form;
	}

}

