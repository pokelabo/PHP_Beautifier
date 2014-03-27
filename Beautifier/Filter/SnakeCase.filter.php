<?php

/**
 * Filter SnakeCase: convert symbol of variables to _snake_case_.
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

use Camel\CaseTransformer;
use Camel\Format;

class PHP_Beautifier_Filter_SnakeCase extends PHP_Beautifier_Filter {
    // bare symbol(class name, dereference of (method|variable), etc.)
    public function t_string($sTag) {
        // an instance variable?
        if ($this->isInstanceVariablePattern($sTag)) {
            $sTag = $this->toSnake($sTag);
        }
        $this->oBeaut->add($sTag);
    }

    // variable
    public function t_variable($sTag) {
        if (!$this->isSpecialVariablePattern($sTag)) {
            $sTag = $this->toSnake($sTag);
        }
        $this->oBeaut->add($sTag);
    }

    // string literal with single quotes
    public function t_constant_encapsed_string($sTag) {
        // array key?
        // # currently we don't care about concatination (i.e. `$a['x' . 'y']`).
        if ($this->oBeaut->isNextTokenConstant(T_DOUBLE_ARROW) ||
            $this->oBeaut->isPreviousTokenConstant('[')) {
            if (!$this->isScreamingSnakeCase($sTag)) {
                $sTag = $this->toSnake($sTag);
            }
        }
        $this->oBeaut->add($sTag);
    }

    public function toSnake2($str) {
        $transformer = $this->getTransformer();
        return $transformer->transform($str);
    }

    public function toSnake($str) {
        if (!is_string($str)) return $str;

        $is_first = true;
        $callback = function($m) use(&$is_first) {
            $len = strlen($m[1]);
            $s = strtolower($m[1]);
            if ($len === 1) { // 先頭一文字のみ大文字
                if ($is_first) {
                    $is_first = false;
                    if ($m[2] === null) {
                        return $s;
                    } else {
                        return $s . $m[2];
                    }
                } else {
                    if ($m[2] === null) {
                        return '_' . $s;
                    } else {
                        return '_' . $s . $m[2];
                    }
                }
            }

            // 大文字が連続している

            if ($m[2] === '') {   // 最後まで大文字
                if ($is_first) {
                    $is_first = false;
                    return $s;
                } else {
                    return '_' . $s;
                }
            } else {                // 小文字・数字も続く
                if ($is_first) {
                    $is_first = false;
                    return substr($s, 0, $len - 1) . '_' . $s[$len - 1] . $m[2];
                } else {
                    return '_' . substr($s, 0, $len - 1) . '_' . $s[$len - 1] . $m[2];
                }
            }
        };

        // 先頭が大文字英数以外の場合に対応
        // 例) $_userName => $_user_name
        $leading_str = '';
        if (preg_match('/^([^A-Za-z]+)/', $str, $m)) {
            $leading_str = $m[1];
            $str = substr($str, strlen($m[1]));
        }
        if (preg_match('/^([^A-Z]+)(?=[A-Z])/', $str, $m)) {
            $leading_str .= $m[1] . '_';
            $str = substr($str, strlen($m[1]));
        }
        return  $leading_str . preg_replace_callback('/([A-Z]+)([0-9a-z]*)/', $callback, ucfirst($str));
    }

    private function isInstanceVariablePattern($sTag) {
        return 0 < preg_match('/^\$?_[a-zA-Z0-9_]+$/', $sTag);
    }

    private function isSpecialVariablePattern($sTag) {
        return preg_match('/^\$_?[A-Z]+$/', $sTag);
    }

    private function isScreamingSnakeCase($sTag) {
        return preg_match('/^([\'"])[A-Z_]+\1$/', $sTag);
    }

    private function getTransformer() {
        if (empty($this->_transformer)) {
            $this->_transformer = new Camel\CaseTransformer(new Format\CamelCase, new Format\SnakeCase);
        }
        return $this->_transformer;
    }
}
