<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

class Migration_Legal_contents extends CI_Migration {
    public function up()
    {
        $this->db->insert('wb_settings', ['name' => 'display_cookie_notice', 'value' => '0']);
        $this->db->insert('wb_settings', ['name' => 'cookie_notice_content', 'value' => 'Cookie notice content.']);
        $this->db->insert('wb_settings', ['name' => 'display_terms_and_conditions', 'value' => '0']);
        $this->db->insert('wb_settings',
            ['name' => 'terms_and_conditions_content', 'value' => 'Terms and conditions content.']);
        $this->db->insert('wb_settings', ['name' => 'display_privacy_policy', 'value' => '0']);
        $this->db->insert('wb_settings', ['name' => 'privacy_policy_content', 'value' => 'Privacy policy content.']);

        $this->db->query('
            CREATE TABLE IF NOT EXISTS `wb_consents` (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `modified` DATETIME DEFAULT CURRENT_TIMESTAMP
                ON UPDATE CURRENT_TIMESTAMP,
                `first_name` VARCHAR(256),
                `last_name` VARCHAR(256),
                `email` VARCHAR(512),
                `ip` VARCHAR(256),
                `type` VARCHAR(256),
                PRIMARY KEY (`id`)
            )
                ENGINE = InnoDB
                DEFAULT CHARSET = utf8;
        ');
    }

    public function down()
    {
        $this->db->delete('wb_settings', ['name' => 'display_cookie_notice']);
        $this->db->delete('wb_settings', ['name' => 'cookie_notice_content']);
        $this->db->delete('wb_settings', ['name' => 'display_terms_and_conditions']);
        $this->db->delete('wb_settings', ['name' => 'terms_and_conditions_content']);
        $this->db->delete('wb_settings', ['name' => 'display_privacy_policy']);
        $this->db->delete('wb_settings', ['name' => 'privacy_policy_content']);

        $this->db->query('DROP TABLE `wb_consents`;');
    }
}
