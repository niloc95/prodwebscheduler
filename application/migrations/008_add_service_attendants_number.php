<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

class Migration_Add_service_attendants_number extends CI_Migration {
    public function up()
    {
        if ( ! $this->db->field_exists('attendants_number', 'wb_services'))
        {
            $fields = [
                'attendants_number' => [
                    'type' => 'INT',
                    'constraint' => '11',
                    'default' => '1',
                    'after' => 'availabilities_type'
                ]
            ];

            $this->dbforge->add_column('wb_services', $fields);

            $this->db->update('wb_services', ['attendants_number' => '1']);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('attendants_number', 'wb_services'))
        {
            $this->dbforge->drop_column('wb_services', 'attendants_number');
        }
    }
}
