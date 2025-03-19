<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

    /**
     * Reference to the CI singleton
     *
     * @var	object
     */
    private static $instance;

    /**
     * Class constructor
     *
     * @return	void
     */
    public function __construct() {
        self::$instance = & $this;
        // Assign all the class objects that were instantiated by the
        // bootstrap file (CodeIgniter.php) to local class variables
        // so that CI can run as one big super object.
        foreach (is_loaded() as $var => $class) {
            $this->$var = & load_class($class);
        }

        $this->load = & load_class('Loader', 'core');
        $this->load->initialize();

        // change by k
        $this->set_cookies();
        $this->path_to_view_admin = ADMINPATH . '/';
        $this->admin_js = base_url() . 'application/views/' . ADMINPATH . '/js/';
        $this->admin_img = base_url() . 'application/views/' . ADMINPATH . '/images/';
        $this->admin_css = base_url() . 'application/views/' . ADMINPATH . '/css/';
        $this->admin_fonts = base_url() . 'application/views/' . ADMINPATH . '/fonts/';
        $this->js = base_url() . 'application/views/' . ADMINPATH . '/js/';

        $this->path_to_view_front = 'themes/' . $this->system->template . '/';
        $this->template_js = base_url() . 'application/views/themes/' . $this->system->template . '/assest/js/';
        $this->template_img = base_url() . 'application/views/themes/' . $this->system->template . '/assest/img/';
        $this->template_css = base_url() . 'application/views/themes/' . $this->system->template . '/assest/css/';
        $this->template_fonts = base_url() . 'application/views/themes/' . $this->system->template . '/assest/fonts/';

        if ($this->member->front_logged_in == true) {
            $this->db->select('*');
            $this->db->where('member_id', $this->member->front_member_id);
            $qry = $this->db->get('member');
            $mem_data = $qry->row_array(); 
            
            if(empty($mem_data)){
                $mem_data['user_template'] = 'bmuseradmin';
            }
        } else {
            $mem_data['user_template'] = 'bmuseradmin';
        }

            $this->path_to_default = $this->system->user_panel . '/';
            $this->path_to_view_default = 'themes/' . $this->system->template . '/' . $mem_data['user_template'] . '/';
            $this->default_js = base_url() . 'application/views/themes/' . $this->system->template . '/' . $mem_data['user_template'] . '/js/';
            $this->default_img = base_url() . 'application/views/themes/' . $this->system->template . '/' . $mem_data['user_template'] . '/images/';
            $this->default_css = base_url() . 'application/views/themes/' . $this->system->template . '/' . $mem_data['user_template'] . '/css/';
            $this->default_fonts = base_url() . 'application/views/themes/' . $this->system->template . '/' . $mem_data['user_template'] . '/fonts/';

        // $this->path_to_default = $this->system->user_panel . '/';
        // $this->path_to_view_default = 'themes/' . $this->system->template . '/' . $this->system->user_template . '/';
        // $this->default_js = base_url() . 'application/views/themes/' . $this->system->template . '/' . $this->system->user_template . '/js/';
        // $this->default_img = base_url() . 'application/views/themes/' . $this->system->template . '/' . $this->system->user_template . '/images/';
        // $this->default_css = base_url() . 'application/views/themes/' . $this->system->template . '/' . $this->system->user_template . '/css/';
        // $this->default_fonts = base_url() . 'application/views/themes/' . $this->system->template . '/' . $this->system->user_template . '/fonts/';

        $this->screenshot_image = $this->system->admin_photo . '/screenshot_image/';
        $this->download_image = $this->system->admin_photo . '/download_image/';
        $this->match_banner_image = $this->system->admin_photo . '/match_banner_image/';
        $this->game_image = $this->system->admin_photo . '/game_image/';
        $this->game_logo_image = $this->system->admin_photo . '/game_logo_image/';
        $this->company_image = $this->system->admin_photo . '/company_image/';
        $this->company_favicon = $this->system->admin_photo . '/company_favicon/';
        $this->page_banner = $this->system->admin_photo . '/page_banner/';
        $this->notification_image = $this->system->admin_photo . '/notification_image/';
        $this->select_image = $this->system->admin_photo . '/select_image/';
        $this->lottery_image = $this->system->admin_photo . '/lottery_image/';
        $this->product_image = $this->system->admin_photo . '/product_image/';
        $this->slider_image = $this->system->admin_photo . '/slider_image/';
        $this->banner_image = $this->system->admin_photo . '/banner_image/';
        $this->profile_image = $this->system->admin_photo . '/profile_image/';
        $this->apk = $this->system->admin_photo . '/apk/';
        
        date_default_timezone_set($this->system->timezone);
        
        log_message('info', 'Controller Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Get the CI singleton
     *
     * @static
     * @return	object
     */
    public static function &get_instance() {
        return self::$instance;
    }

    public static function &set_cookies() {
        $CI = & get_instance();
		//nulled
	    setcookie('bmpcs', 'yes', time() + 86400 * 7, '/', parse_url(current_url())['host']);
	    $_COOKIE ['bmpcs'] = 'yes';

        return $CI;
    }

}
