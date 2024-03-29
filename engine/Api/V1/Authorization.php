<?php

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.0
 * ---------------------------------------------------------------------------- */

namespace WB\Engine\Api\V1;

use \WB\Engine\Types\NonEmptyText;

/**
 * API v1 Authorization Class
 *
 * This class will handle the authorization procedure of the API.
 */
class Authorization {
    /**
     * Framework Instance
     *
     * @var CI_Controller
     */
    protected $framework;

    /**
     * Class Constructor
     *
     * @param \CI_Controller $framework
     */
    public function __construct(\CI_Controller $framework)
    {
        $this->framework = $framework;
    }

    /**
     * Perform Basic Authentication
     *
     * @param NonEmptyText $username Admin Username
     * @param NonEmptyText $password Admin Password
     *
     * @throws \WB\Engine\Api\V1\Exception Throws 401-Unauthorized exception if the authentication fails.
     */
    public function basic(NonEmptyText $username, NonEmptyText $password)
    {
        $this->framework->load->model('user_model');

        if ( ! $this->framework->user_model->check_login($username->get(), $password->get()))
        {
            throw new Exception('The provided credentials do not match any admin user!', 401, 'Unauthorized');
        }
    }
}
