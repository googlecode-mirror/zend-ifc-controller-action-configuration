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
 * HeadScriptFile
 */
class HeadScriptFile extends HelperAbstract {

    /**
     * @var Zend_View_Helper_HeadScript
     */
    protected $_headScript = null;

  /**
     * Strategy pattern: Initialize the configuration.
     *
     * Change the script tags that are linked to a file used in the web page
     * according the configuration pararmeters
     *
     * See {@link \Zend_View}
     * See {@link \Zend_View_Helper_HeadScript}
     *
     * @param \Zend_Controller_Request_Abstract $request
     */
    public function init(\Zend_Controller_Request_Abstract $request) {

        $this->_headScript = $this->_getView()->headScript();

        foreach ($this->_options as $scriptFileId => $options) {

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

    public function getHeadScript() {
        return $this->_headScript;
    }

}
