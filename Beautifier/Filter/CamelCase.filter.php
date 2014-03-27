<?php

/**
 * Filter CamelCase: convert symbol of variables to _camelCase_.
 *
 * PHP version 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   PHP
 * @package    PHP_Beautifier
 * @subpackage Filter
 * @author     HANAI Tohru <pokehanai@pokelabo.co.jp>
 * @copyright  2014 HANAI Tohru
 * @license    http://www.php.net/license/3_0.txt PHP License 3.0
 * @version    1.0
 * @link       http://pear.php.net/package/PHP_Beautifier
 * @link       http://beautifyphp.sourceforge.net
 */

class PHP_Beautifier_Filter_CamelCase extends PHP_Beautifier_Filter {
    // bare symbol(class name, dereference of (method|variable), etc.)
    public function t_string($sTag) {
        // an instance variable?
        if ($this->isInstanceVariablePattern($sTag) &&
            $this->isSnakeCase($sTag)) {
            $sTag = $this->toCamel($sTag);
        }
        $this->oBeaut->add($sTag);
    }

    // variable
    public function t_variable($sTag) {
        if (!$this->isSpecialVariablePattern($sTag) &&
            $this->isSnakeCase($sTag)) {
            $sTag = $this->toCamel($sTag);
        }
        $this->oBeaut->add($sTag);
    }

    // string literal with single quotes
    public function t_constant_encapsed_string($sTag) {
        // array key?
        // # currently we don't care about concatination (i.e. `$a['x' . 'y']`).
        if ($this->oBeaut->isNextTokenConstant(T_DOUBLE_ARROW) ||
            $this->oBeaut->isPreviousTokenConstant('[')) {
            if (!$this->isScreamingSnakeCase($sTag) &&
                $this->isSnakeCase($sTag)) {
                $sTag = $this->toCamel($sTag);
            }
        }
        $this->oBeaut->add($sTag);
    }

    private function toCamel($str, $ucfirst = false) {
        if (!is_string($str)) return $str;

        $is_first = true;
        $callback = function($m) use($ucfirst, &$is_first) {
            $s = strtolower($m[1]);
            if ($is_first) {
                $is_first = false;
                return $ucfirst ? ucfirst($s) : $s;
            } else {
                return ucfirst($s);
            }
        };

        return preg_replace_callback('/([[:alnum:]]+)(_*)/', $callback, $str);
    }

    private function isInstanceVariablePattern($sTag) {
        return 0 < preg_match('/^\$?_[a-z0-9_]+$/', $sTag);
    }

    private function isSpecialVariablePattern($sTag) {
        return preg_match('/^\$_?[A-Z]+$/', $sTag);
    }

    private function isScreamingSnakeCase($sTag) {
        return preg_match('/^([\'"])[A-Z_]+\1$/', $sTag);
    }

    private function isSnakeCase($sTag) {
        return preg_match('/[[[:alnum:]]_[[[:alnum:]]/', $sTag);
    }
}
