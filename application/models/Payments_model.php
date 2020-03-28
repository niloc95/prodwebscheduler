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

 * Payments Model

 *

 * @package Models

 */

class Payments_Model extends CI_Model {

    /**

     * Add an payment record to the database.

     *

     * This method adds a new payment to the database. If the payment doesn't exists it is going to be inserted,

     * otherwise the record is going to be updated.

     *

     * @param array $payment Associative array with the payment data. Each key has the same name with the

     * database fields.

     *

     * @return int Returns the payments id.

     */

    public function add($payment, $signature_hash_data)

    {
        $payment_id = $this->generate_hash();

        $signature_hash_data['m_payment_id'] = $payment_id;
        
        $signature = $this->payments_model->generate_signature_hash($signature_hash_data);

        $payment['signature'] = $signature;

        $payment['payment_id'] = $payment_id;


        // Perform insert() operation.

        if ( ! isset($payment['id']))

        {

            $this->_insert($payment);

        }

        else

        {

            $this->_update($payment);

        }

        $response = array(
            "payment_id" => $payment_id,
            "signature" => $signature
        );

        return $response;

    }


    public function update($payment_id)

    {
        
        $this->_update($payment_id);

    }



    /**

     * Check if a particular payment record already exists.

     *

     * This method checks whether the given payment already exists in the database. It doesn't search with the id,

     * but by using the following fields: "start_datetime", "end_datetime", "id_users_provider", "id_users_customer",

     * "id_services".

     *

     * @param array $payment Associative array with the payment's data. Each key has the same name with the

     * database fields.

     *

     * @return bool Returns whether the record exists or not.

     *

     * @throws Exception If payment fields are missing.

     */


    public function exists($payment)

    {

        if ( ! isset($payment['start_datetime'])

            || ! isset($payment['end_datetime'])

            || ! isset($payment['id_users_provider'])

            || ! isset($payment['id_users_customer'])

            || ! isset($payment['id_services']))

        {

            throw new Exception('Not all payment field values are provided: '

                . print_r($payment, TRUE));

        }



        $num_rows = $this->db->get_where('wb_appoinments', [

            'start_datetime' => $payment['start_datetime'],

            'end_datetime' => $payment['end_datetime'],

            'id_users_provider' => $payment['id_users_provider'],

            'id_users_customer' => $payment['id_users_customer'],

            'id_services' => $payment['id_services'],

        ])

            ->num_rows();



        return ($num_rows > 0) ? TRUE : FALSE;

    }



    /**

     * Insert a new payment record to the database.

     *

     * @param array $payment Associative array with the payment's data. Each key has the same name with the

     * database fields.

     *

     * @return int Returns the id of the new record.

     *

     * @throws Exception If payment record could not be inserted.

     */

    protected function _insert($payment)

    {
        $payment['payment_id'] = $this->generate_hash();



        if ( ! $this->db->insert('wb_payments', $payment))

        {

            throw new Exception('Could not insert payment record.');

        }



        return $payment['payment_id'];

    }



    /**

     * Update an existing payment record in the database.

     *

     * The payment data argument should already include the record ID in order to process the update operation.

     *

     * @param array $payment Associative array with the payment's data. Each key has the same name with the

     * database fields.

     *

     * @throws Exception If payment record could not be updated.

     */

    protected function _update($payment_id)

    {

        $this->db->where('payment_id', $payment_id);

        if ( ! $this->db->update('wb_payments', ['status' => 'PAID']))

        {

            throw new Exception('Could not update payment record.');

        }

    }



    /**

     * Find the database id of an payment record.

     *

     * The payment data should include the following fields in order to get the unique id from the database:

     * "start_datetime", "end_datetime", "id_users_provider", "id_users_customer", "id_services".

     *

     * IMPORTANT: The record must already exists in the database, otherwise an exception is raised.

     *

     * @param array $payment Array with the payment data. The keys of the array should have the same names as

     * the db fields.

     *

     * @return int Returns the db id of the record that matches the payment data.

     *

     * @throws Exception If payment could not be found.

     */

    public function find_record_id($payment)

    {

        $this->db->where([

            'start_datetime' => $payment['start_datetime'],

            'end_datetime' => $payment['end_datetime'],

            'id_users_provider' => $payment['id_users_provider'],

            'id_users_customer' => $payment['id_users_customer'],

            'id_services' => $payment['id_services']

        ]);



        $result = $this->db->get('wb_appoinments');



        if ($result->num_rows() == 0)

        {

            throw new Exception('Could not find payment record id.');

        }



        return $result->row()->id;

    }



    /**

     * Validate payment data before the insert or update operations are executed.

     *

     * @param array $payment Contains the payment data.

     *

     * @return bool Returns the validation result.

     *

     * @throws Exception If payment validation fails.

     */

    public function validate($payment)

    {

        $this->load->helper('data_validation');



        // If a payment id is given, check whether the record exists

        // in the database.

        if (isset($payment['signature']))

        {

            $num_rows = $this->db->get_where('wb_payments',

                ['payment_id' => $payment['payment_id']])->num_rows();

            if ($num_rows != 0)

            {

                throw new Exception('Provided payment id does not exist in the database.');

            }

        }

        return TRUE;

    }



    /**

     * Delete an existing payment record from the database.

     *

     * @param int $payment_id The record id to be deleted.

     *

     * @return bool Returns the delete operation result.

     *

     * @throws Exception If $payment_id argument is invalid.

     */

    public function delete($payment_id)

    {

        if ( ! is_numeric($payment_id))

        {

            throw new Exception('Invalid argument type $payment_id (value:"' . $payment_id . '")');

        }



        $num_rows = $this->db->get_where('wb_appoinments', ['id' => $payment_id])->num_rows();



        if ($num_rows == 0)

        {

            return FALSE; // Record does not exist.

        }



        $this->db->where('id', $payment_id);

        return $this->db->delete('wb_appoinments');

    }



    /**

     * Get a specific row from the payments table.

     *

     * @param int $payment_id The record's id to be returned.

     *

     * @return array Returns an associative array with the selected record's data. Each key has the same name as the

     * database field names.

     *

     * @throws Exception If $payment_id argumnet is invalid.

     */

    public function get_row($payment_id)

    {

        if ( ! is_numeric($payment_id))

        {

            throw new Exception('Invalid argument given. Expected integer for the $payment_id: '

                . $payment_id);

        }



        return $this->db->get_where('wb_appoinments', ['id' => $payment_id])->row_array();

    }



    /**

     * Get a specific field value from the database.

     *

     * @param string $field_name The field name of the value to be returned.

     * @param int $payment_id The selected record's id.

     *

     * @return string Returns the records value from the database.

     *

     * @throws Exception If $payment_id argument is invalid.

     * @throws Exception If $field_name argument is invalid.

     * @throws Exception If requested payment record was not found.

     * @throws Exception If requested field name does not exist.

     */

    public function get_value($field_name, $payment_id)

    {

        if ( ! is_numeric($payment_id))

        {

            throw new Exception('Invalid argument given, expected integer for the $payment_id: '

                . $payment_id);

        }



        if ( ! is_string($field_name))

        {

            throw new Exception('Invalid argument given, expected  string for the $field_name: ' . $field_name);

        }



        if ($this->db->get_where('wb_appoinments', ['id' => $payment_id])->num_rows() == 0)

        {

            throw new Exception('The record with the provided id '

                . 'does not exist in the database: ' . $payment_id);

        }



        $row_data = $this->db->get_where('wb_appoinments', ['id' => $payment_id])->row_array();



        if ( ! isset($row_data[$field_name]))

        {

            throw new Exception('The given field name does not exist in the database: ' . $field_name);

        }



        return $row_data[$field_name];

    }



    /**

     * Get all, or specific records from payment's table.

     *

     * @example $this->Model->getBatch('id = ' . $recordId);

     *

     * @param string $where_clause (OPTIONAL) The WHERE clause of the query to be executed. DO NOT INCLUDE 'WHERE'

     * KEYWORD.

     *

     * @param bool $aggregates (OPTIONAL) Defines whether to add aggregations or not.

     *

     * @return array Returns the rows from the database.

     */

    public function get_batch($where_clause = '', $aggregates = FALSE)

    {

        if ($where_clause != '')

        {

            $this->db->where($where_clause);

        }



        $payments = $this->db->get('wb_appoinments')->result_array();



        if ($aggregates)

        {

            foreach ($payments as &$payment)

            {

                $payment = $this->get_aggregates($payment);

            }

        }



        return $payments;

    }



    /**

     * Generate a unique hash for the given payment data.

     *

     * This method uses the current date-time to generate a unique hash string that is later used to identify this

     * payment. Hash is needed when the email is send to the user with an edit link.

     *

     * @return string Returns the unique payment hash.

     */

    public function generate_hash()

    {

        $current_date = new DateTime();

        return md5($current_date->getTimestamp());

    }

    public function generate_signature_hash($signature_hash_data)
    {

        $pfParamString = '';

         // Dump the submitted variables and calculate security signature
        foreach ( $signature_hash_data as $key => $val )
        {
            if ( $key != 'signature' )
            {
                $pfParamString .= $key . '=' . urlencode( $val ) . '&';
            }
        }

        // Remove the last '&' from the parameter string
        $pfParamString = substr( $pfParamString, 0, -1 );

        $pfTempParamString = $pfParamString;

        $signature = md5( $pfTempParamString );

        return $signature;

    }



    /**

     * Get payment count for the provided start datetime.

     *

     * @param int $service_id Selected service ID.

     * @param string $selected_date Selected date string.

     * @param string $hour Selected hour string.

     *

     * @return int Returns the payment number at the selected start time.

     */

    public function payment_count_for_hour($service_id, $selected_date, $hour)

    {

        return $this->db->get_where('wb_appoinments', [

            'id_services' => $service_id,

            'start_datetime' => date('Y-m-d H:i:s', strtotime($selected_date . ' ' . $hour . ':00'))

        ])->num_rows();

    }



    /**

     * Returns the attendants number for selection period.

     *

     * @param DateTime $slot_start When the slot starts

     * @param DateTime $slot_end When the slot ends.

     * @param int $service_id Selected service ID.

     *

     * @return int Returns the number of attendants for selected time period.

     */

    public function get_attendants_number_for_period(DateTime $slot_start, DateTime $slot_end, $service_id)

    {

        return (int)$this->db

            ->select('count(*) AS attendants_number')

            ->from('wb_appoinments')

            ->group_start()

            ->where('start_datetime <=', $slot_start->format('Y-m-d H:i:s'))

            ->where('end_datetime >', $slot_start->format('Y-m-d H:i:s'))

            ->group_end()

            ->or_group_start()

            ->where('start_datetime <', $slot_end->format('Y-m-d H:i:s'))

            ->where('end_datetime >=', $slot_end->format('Y-m-d H:i:s'))

            ->group_end()

            ->where('id_services', $service_id)

            ->get()

            ->row()

            ->attendants_number;

    }



    /**

     * Get the aggregates of an payment.

     *

     * @param array $payment Appointment data.

     *

     * @return array Returns the payment with the aggregates.

     */

    private function get_aggregates(array $payment)

    {

        $payment['service'] = $this->db->get_where('wb_services',

            ['id' => $payment['id_services']])->row_array();

        $payment['provider'] = $this->db->get_where('wb_users',

            ['id' => $payment['id_users_provider']])->row_array();

        $payment['customer'] = $this->db->get_where('wb_users',

            ['id' => $payment['id_users_customer']])->row_array();

        return $payment;

    }

}

