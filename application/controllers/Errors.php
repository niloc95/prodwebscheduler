<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

/**
 * Errors Controller
 *
 * @package Controllers
 */
class Errors extends CI_Controller {
    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->library('session');

        // Set user's selected language.
        if ($this->session->userdata('language'))
        {
            $this->config->set_item('language', $this->session->userdata('language'));
            $this->lang->load('translations', $this->session->userdata('language'));
        }
        else
        {
            $this->lang->load('translations', $this->config->item('language')); // default
        }
    }

    /**
     * Display the 404 error page.
     */
    public function index()
    {
        $this->e404();
    }

    /**
     * Display the 404 error page.
     */
    public function error404()
    {
        $this->load->helper('google_analytics');
        $this->load->model('settings_model');
        $view['company_name'] = $this->settings_model->get_setting('company_name');
        $this->load->view('general/error404', $view);
    }
}
