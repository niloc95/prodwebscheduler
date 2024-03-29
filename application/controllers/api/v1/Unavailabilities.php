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
use \WB\Engine\Types\NonEmptyText;

/**
 * Unavailabilities Controller
 *
 * @package Controllers
 * @subpackage API
 */
class Unavailabilities extends API_V1_Controller {
    /**
     * Unavailabilities Resource Parser
     *
     * @var \WB\Engine\Api\V1\Parsers\Unavailabilities
     */
    protected $parser;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('appointments_model');
        $this->parser = new \WB\Engine\Api\V1\Parsers\Unavailabilities;
    }

    /**
     * GET API Method
     *
     * @param int $id Optional (null), the record ID to be returned.
     */
    public function get($id = NULL)
    {
        try
        {
            $condition = $id !== NULL ? 'id = ' . $id : 'is_unavailable = 1';
            $unavailabilities = $this->appointments_model->get_batch($condition);

            if ($id !== NULL && count($unavailabilities) === 0)
            {
                $this->_throwRecordNotFound();
            }

            $response = new Response($unavailabilities);

            $response->encode($this->parser)
                ->search()
                ->sort()
                ->paginate()
                ->minimize()
                ->singleEntry($id)
                ->output();

        }
        catch (\Exception $exception)
        {
            exit($this->_handleException($exception));
        }
    }

    /**
     * POST API Method
     */
    public function post()
    {
        try
        {
            // Insert the appointment to the database.
            $request = new Request();
            $unavailability = $request->getBody();
            $this->parser->decode($unavailability);

            if (isset($unavailability['id']))
            {
                unset($unavailability['id']);
            }

            $id = $this->appointments_model->add_unavailable($unavailability);

            // Fetch the new object from the database and return it to the client.
            $batch = $this->appointments_model->get_batch('id = ' . $id);
            $response = new Response($batch);
            $status = new NonEmptyText('201 Created');
            $response->encode($this->parser)->singleEntry(TRUE)->output($status);
        }
        catch (\Exception $exception)
        {
            exit($this->_handleException($exception));
        }
    }

    /**
     * PUT API Method
     *
     * @param int $id The record ID to be updated.
     */
    public function put($id)
    {
        try
        {
            // Update the appointment record.
            $batch = $this->appointments_model->get_batch('id = ' . $id);

            if ($id !== NULL && count($batch) === 0)
            {
                $this->_throwRecordNotFound();
            }

            $request = new Request();
            $updatedUnavailability = $request->getBody();
            $baseUnavailability = $batch[0];
            $this->parser->decode($updatedUnavailability, $baseUnavailability);
            $updatedUnavailability['id'] = $id;
            $id = $this->appointments_model->add_unavailable($updatedUnavailability);

            // Fetch the updated object from the database and return it to the client.
            $batch = $this->appointments_model->get_batch('id = ' . $id);
            $response = new Response($batch);
            $response->encode($this->parser)->singleEntry($id)->output();
        }
        catch (\Exception $exception)
        {
            exit($this->_handleException($exception));
        }
    }

    /**
     * DELETE API Method
     *
     * @param int $id The record ID to be deleted.
     */
    public function delete($id)
    {
        try
        {
            $result = $this->appointments_model->delete_unavailable($id);

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
