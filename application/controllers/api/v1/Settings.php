<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.0
 * ---------------------------------------------------------------------------- */

require_once __DIR__ . '/API_V1_Controller.php';

use \WB\Engine\Api\V1\Response;
use \WB\Engine\Api\V1\Request;

/**
 * Settings Controller
 *
 * @package Controllers
 * @subpackage API
 */
class Settings extends API_V1_Controller {
    /**
     * Settings Resource Parser
     *
     * @var \WB\Engine\Api\V1\Parsers\Settings
     */
    protected $parser;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
        $this->parser = new \WB\Engine\Api\V1\Parsers\Settings;
    }

    /**
     * GET API Method
     *
     * @param string $name Optional (null), the setting name to be returned.
     */
    public function get($name = NULL)
    {
        try
        {
            $settings = $this->settings_model->get_settings();

            if ($name !== NULL)
            {
                $setting = NULL;

                foreach ($settings as $entry)
                {
                    if ($entry['name'] === $name)
                    {
                        $setting = $entry;
                        break;
                    }
                }

                if (empty($setting))
                {
                    $this->_throwRecordNotFound();
                }

                unset($setting['id']);

                $settings = [
                    $setting
                ];
            }

            $response = new Response($settings);

            $response->encode($this->parser)
                ->search()
                ->sort()
                ->paginate()
                ->minimize()
                ->singleEntry($name)
                ->output();

        }
        catch (\Exception $exception)
        {
            exit($this->_handleException($exception));
        }
    }

    /**
     * PUT API Method
     *
     * @param string $name The setting name to be inserted/updated.
     */
    public function put($name)
    {
        try
        {
            $request = new Request();
            $value = $request->getBody()['value'];
            $this->settings_model->set_setting($name, $value);

            // Fetch the updated object from the database and return it to the client.
            $response = new Response([
                [
                    'name' => $name,
                    'value' => $value
                ]
            ]);
            $response->encode($this->parser)->singleEntry($name)->output();
        }
        catch (\Exception $exception)
        {
            exit($this->_handleException($exception));
        }
    }

    /**
     * DELETE API Method
     *
     * @param string $name The setting name to be deleted.
     */
    public function delete($name)
    {
        try
        {
            $result = $this->settings_model->remove_setting($name);

            $response = new Response([
                'code' => 200,
                'message' => 'Record was deleted successfully!'
            ]);

            $response->output();
        }
        catch (\Exception $exception)
        {
            exit($this->_handleException($exception));
        }
    }
}
