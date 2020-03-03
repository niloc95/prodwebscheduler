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
 * Customers Controller
 *
 * @package Controllers
 * @subpackage API
 */
class Customers extends API_V1_Controller {
    /**
     * Customers Resource Parser
     *
     * @var \WB\Engine\Api\V1\Parsers\Customers
     */
    protected $parser;

    /**
     * Class Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('customers_model');
        $this->parser = new \WB\Engine\Api\V1\Parsers\Customers;
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
            $condition = $id !== NULL ? 'id = ' . $id : NULL;
            $customers = $this->customers_model->get_batch($condition);

            if ($id !== NULL && count($customers) === 0)
            {
                $this->_throwRecordNotFound();
            }

            $response = new Response($customers);

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
            $this->_handleException($exception);
        }
    }

    /**
     * POST API Method
     */
    public function post()
    {
        try
        {
            // Insert the customer to the database.
            $request = new Request();
            $customer = $request->getBody();
            $this->parser->decode($customer);

            if (isset($customer['id']))
            {
                unset($customer['id']);
            }

            $id = $this->customers_model->add($customer);

            // Fetch the new object from the database and return it to the client.
            $batch = $this->customers_model->get_batch('id = ' . $id);
            $response = new Response($batch);
            $status = new NonEmptyText('201 Created');
            $response->encode($this->parser)->singleEntry(TRUE)->output($status);
        }
        catch (\Exception $exception)
        {
            $this->_handleException($exception);
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
            // Update the customer record.
            $batch = $this->customers_model->get_batch('id = ' . $id);

            if ($id !== NULL && count($batch) === 0)
            {
                $this->_throwRecordNotFound();
            }

            $request = new Request();
            $updatedCustomer = $request->getBody();
            $baseCustomer = $batch[0];
            $this->parser->decode($updatedCustomer, $baseCustomer);
            $updatedCustomer['id'] = $id;
            $id = $this->customers_model->add($updatedCustomer);

            // Fetch the updated object from the database and return it to the client.
            $batch = $this->customers_model->get_batch('id = ' . $id);
            $response = new Response($batch);
            $response->encode($this->parser)->singleEntry($id)->output();
        }
        catch (\Exception $exception)
        {
            $this->_handleException($exception);
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
            $result = $this->customers_model->delete($id);

            $response = new Response([
                'code' => 200,
                'message' => 'Record was deleted successfully!'
            ]);

            $response->output();
        }
        catch (\Exception $exception)
        {
            $this->_handleException($exception);
        }
    }
}
