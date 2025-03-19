<?php

class AppConfigHook {

    protected $obj;

    public function __construct() {
        $this->obj = & get_instance();
    }

    function Setup() {
    setcookie('bmpcs', 'yes', time() + 86400 * 7, '/', parse_url(current_url())['host']);
    $_COOKIE ['bmpcs'] = 'yes';
	}
}