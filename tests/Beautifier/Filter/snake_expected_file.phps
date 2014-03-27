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
        $camel_case = 3;
        $_camel_case = 4;
        $studly_caps = 5;
        $_studly_caps = 6;

        $a['camel_case'] = 11;
        $a['_camel_case'] = 12;
        $a['studly_caps'] = 13;
        $a['_studly_caps'] = 14;

        $a = array('camel_case' => 21, '_camel_case' => 22, 'studly_caps' => 23, '_studly_caps' => 24);
    }
}
