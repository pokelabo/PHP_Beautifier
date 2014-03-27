<?php

class Hogehoge {
    public $_foo_bar = 1;
    protected $_foo_bar2;
    private $_foo_bar3 = array(1, 2, 3);

    public static $_foo_bar4;

    public function __construct() {
        $this->_foo_bar = 3;
        self::$_foo_bar4 = 700;

        $a['foo_bar'] = 'foo_bar';
        $b = array('foo_bar' => 1, "foo_bar" => 2);
    }

    public function alreadyConvertedToCamelCase() {
        $camelCase = 3;
        $_camelCase  = 4;
        $StudlyCaps = 5;
        $_StudlyCaps = 6;

        $a['camelCase'] = 11;
        $a['_camelCase'] = 12;
        $a['StudlyCaps'] = 13;
        $a['_StudlyCaps'] = 14;

        $a = array('camelCase' => 21,
                   '_camelCase' => 22,
                   'StudlyCaps' => 23,
                   '_StudlyCaps' => 24);
    }
}
