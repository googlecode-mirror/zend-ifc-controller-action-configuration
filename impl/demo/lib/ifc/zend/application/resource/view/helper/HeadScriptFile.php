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
 * HeadScriptFile
 */
class HeadScriptFile extends HelperAbstract {

    /**
     * @var Zend_View_Helper_HeadScript
     */
    protected $_headScript = null;

    public function init() {
        return $this->getHeadScript();
    }

    public function getHeadScript() {

        if ($this->_headScript === null) {

            $this->_headScript = $this->_getView()->headScript();
            $scriptFilesDef = $this->getOptions();

            foreach ($scriptFilesDef as $scriptFileId => $options) {

                if (!isset($options['src'])) {
                    require_once 'Exception.php';
                    throw new Exception('The definition of a script file element require at least
                            one minimum option parameter:\"src\"');
                }

                if (!isset($options['type'])) {
                    $type = 'text/javascript';
                } else {
                    $type = $options['type'];
                }

                if (isset($options['allowArbitraryAttributes'])) {
                    $this->_headScript->setAllowArbitraryAttributes($options['allowArbitraryAttributes']);
                }

                if (!isset($options['attributes'])) {
                    $options['attributes'] = array();
                }


                if (!isset($options['placement']) || (strcasecmp('APPEND', $options['placement']) == 0)) {
                    $this->_headScript->appendFile($options['src'], $type, $options['attributes']);
                } else if (strcasecmp('SET', $options['placement']) == 0) {
                    $this->_headScript->setFile($options['src'], $type, $options['attributes']);
                } else if (strcasecmp('PREPEND', $options['placement']) == 0) {
                    $this->_headScript->prependFile($options['src'], $type, $options['attributes']);
                } else {
                    $this->_headScript->offsetSetFile(intval($options['placement']), $options['src'], $type, $options['attributes']);
                }
            }
        }

        return $this->_headScript;
    }

}
