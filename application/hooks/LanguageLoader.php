<?php

class LanguageLoader {

    function initialize() {
        $ci = & get_instance();
        $ci->load->helper('language');
        $siteLang = $ci->session->userdata('site_lang');

        if ($siteLang) {
            $ci->lang->load('information', $siteLang);
            $ci->lang->load('alert', $siteLang);
            $ci->lang->load('validation', $siteLang);
        } else {
            $ci->lang->load('information', 'english');
            $ci->lang->load('alert', 'english');
            $ci->lang->load('validation', 'english');
        }
    }

}
