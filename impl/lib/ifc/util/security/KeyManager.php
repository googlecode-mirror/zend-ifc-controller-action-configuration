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

namespace ifc\util\security;

/**
 * Description of KeyManager
 * This class implements several static methods for treatment of keys to get
 * more security in storage these
 */
class KeyManager {

    /**
     * Generate a random ASCII string only including:
     * Decimal numbers (48 to 57), upper case letters (65 to 90) and
     * lower case letters (97 to 122).
     *
     * @param int $saltLength The length of the random string to get
     * @return string Salt random string
     */
    public static function visibleAsciiCharsSaltGenerator($saltLength = 16) {

        if (!is_int($saltLength)) {
            throw new Exception('Incorrect parameter type; the method
                only accept an integer value');
        }

        if ($saltLength <= 0) {
            throw new Exception('Parameter Value out of range, the method only
                accept a positive integer value');
        }

        $salt = "";

        for ($n = 0; $n < $saltLength; $n++) {
            srand(microtime(true) * 10000 * $n);
            $asciidec = rand(0, 60);

            if ($asciidec <= 9) {
                $salt .= chr($asciidec + 48);
            } else if ($asciidec <= 35) {
                $salt .= chr($asciidec + 55);
            } else {
                $salt .= chr($asciidec + 62);
            }
        }

        return $salt;
    }

    /**
     * Get a string that mix the password and salt parameter to apply hash
     * algorithm over this and avoid brute force attack over stored hash
     *
     * Use the supplied factor or compute a random factor for splitting the salt
     * string in  chunks and puting the each chunk between each password character,
     * starting from the beggining of password string
     *
     *
     * @param string $password The password to mix with salt
     * @param string $salt The salt to use for mixin
     * @param int $splitterFactor The integer value to use for splitting the salt
     * @return array An array that contains in the first element the splitter factor
     *      used to chunk the salt, and the second element the mixed password
     *      and salt string
     * @throws \ifc\util\security\Exception if parameters don't have a correct
     *      type or correct value range
     */
    public static function pwdAndSaltMixerByPwdLength($password, $salt, $splitterFactor = null) {

        if (!is_string($password) || !is_string($salt)) {
            throw new Exception('Incorrect parameter type; the method
                only accept two string parameters an one integer');
        }

        $pwdLength = strlen($password);
        if ($pwdLength == 0) {
            throw new Exception('The "password" parameter cannnot be an empty string');
        }

        if (!isset($splitterFactor)) {
            srand(microtime(true) * 10000);
            $splitterFactor = rand(1, $pwdLength);
        } else {
            if (!is_int($splitterFactor)
                    || ($splitterFactor <= 0)
                    || ($splitterFactor > $pwdLength)) {

                throw new Exception('Value out of range, the "splitterFactor" parameter
                has to be a positive integer value beetwen 1 and the lenght of
                string of "password" parameter');
            }
        }

        $saltLength = strlen($salt);
        if ($saltLength == 0) {
            $mixedPwd = $password;
        } else {
            if ($saltLength < $splitterFactor) {
                $saltCharPerPosition = $saltLength;
            } else {
                $saltCharPerPosition = intval($saltLength / $splitterFactor);
            }

            $saltChunks = str_split($salt, $saltCharPerPosition);

            $pwdArray = str_split($password);
            $mixedPwd = array();

            foreach ($saltChunks as $chunk) {
                array_push($mixedPwd, $chunk);
                array_push($mixedPwd, array_shift($pwdArray));
            }

            $mixedPwd = implode($mixedPwd) . implode($pwdArray);
        }

        return array($splitterFactor, $mixedPwd);
    }

}

