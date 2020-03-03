<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

class Migration_Specific_calendar_sync extends CI_Migration {

    public function up()
    {
        if ( ! $this->db->field_exists('google_calendar', 'wb_user_settings'))
        {
            $fields = [
                'google_calendar' => [
                    'type' => 'VARCHAR',
                    'constraint' => '128',
                    'null' => TRUE
                ]
            ];
            $this->dbforge->add_column('wb_user_settings', $fields);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('google_calendar', 'wb_user_settings'))
        {
            $this->dbforge->drop_column('wb_user_settings', 'google_calendar');
        }
    }
}
