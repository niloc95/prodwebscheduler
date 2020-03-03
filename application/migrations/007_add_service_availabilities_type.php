<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

class Migration_Add_service_availabilities_type extends CI_Migration {
    public function up()
    {
        if ( ! $this->db->field_exists('availabilities_type', 'wb_services'))
        {
            $fields = [
                'availabilities_type' => [
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                    'default' => 'flexible',
                    'after' => 'description'
                ]
            ];

            $this->dbforge->add_column('wb_services', $fields);

            $this->db->update('wb_services', ['availabilities_type' => 'flexible']);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('availabilities_type', 'wb_services'))
        {
            $this->dbforge->drop_column('wb_services', 'availabilities_type');
        }
    }
}
