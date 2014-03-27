<?php
class Hogehoge {
    public $_fooBar = 1;
    protected $_fooBar2;
    private $_fooBar3 = array(1, 2, 3);

    public static $_fooBar4;

    public function __construct() {
        $this->_fooBar = 3;
        self::$_fooBar4 = 700;

        $a['fooBar'] = 'foo_bar';
        $b = array('fooBar' => 1, "fooBar" => 2);
    }

    public function alreadyConvertedToCamelCase() {
        $camelCase = 3;
        $_camelCase = 4;
        $StudlyCaps = 5;
        $_StudlyCaps = 6;

        $a['camelCase'] = 11;
        $a['_camelCase'] = 12;
        $a['StudlyCaps'] = 13;
        $a['_StudlyCaps'] = 14;

        $a = array('camelCase' => 21, '_camelCase' => 22, 'StudlyCaps' => 23, '_StudlyCaps' => 24);
    }
}
