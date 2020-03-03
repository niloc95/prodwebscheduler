<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

class Migration_Add_calendar_view_setting extends CI_Migration {
    public function up()
    {
        if ( ! $this->db->field_exists('calendar_view', 'wb_user_settings'))
        {
            $fields = [
                'calendar_view' => [
                    'type' => 'VARCHAR',
                    'constraint' => '32',
                    'default' => 'default'
                ]
            ];

            $this->dbforge->add_column('wb_user_settings', $fields);

            $this->db->update('wb_user_settings', ['calendar_view' => 'default']);
        }
    }

    public function down()
    {
        if ($this->db->field_exists('calendar_view', 'wb_user_settings'))
        {
            $this->dbforge->drop_column('wb_user_settings', 'calendar_view_calendar');
        }
    }
}
