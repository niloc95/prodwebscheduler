<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

class Migration_Remove_prefix_from_fkey_constraints extends CI_Migration {
    public function up()
    {
        // Drop table constraints.
        $this->db->query('ALTER TABLE wb_appoinments DROP FOREIGN KEY wb_appoinments_ibfk_2');
        $this->db->query('ALTER TABLE wb_appoinments DROP FOREIGN KEY wb_appoinments_ibfk_3');
        $this->db->query('ALTER TABLE wb_appoinments DROP FOREIGN KEY wb_appoinments_ibfk_4');
        $this->db->query('ALTER TABLE wb_secretaries_providers DROP FOREIGN KEY fk_wb_secretaries_providers_1');
        $this->db->query('ALTER TABLE wb_secretaries_providers DROP FOREIGN KEY fk_wb_secretaries_providers_2');
        $this->db->query('ALTER TABLE wb_services_providers DROP FOREIGN KEY wb_services_providers_ibfk_1');
        $this->db->query('ALTER TABLE wb_services_providers DROP FOREIGN KEY wb_services_providers_ibfk_2');
        $this->db->query('ALTER TABLE wb_services DROP FOREIGN KEY wb_services_ibfk_1');
        $this->db->query('ALTER TABLE wb_users DROP FOREIGN KEY wb_users_ibfk_1');
        $this->db->query('ALTER TABLE wb_user_settings DROP FOREIGN KEY wb_user_settings_ibfk_1');

        // Add table constraints again without the "ea" prefix.
        $this->db->query('ALTER TABLE `wb_appoinments`
            ADD CONSTRAINT `appointments_users_customer` FOREIGN KEY (`id_users_customer`) REFERENCES `wb_users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
            ADD CONSTRAINT `appointments_services` FOREIGN KEY (`id_services`) REFERENCES `wb_services` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
            ADD CONSTRAINT `appointments_users_provider` FOREIGN KEY (`id_users_provider`) REFERENCES `wb_users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE');

        $this->db->query('ALTER TABLE `wb_secretaries_providers`
            ADD CONSTRAINT `secretaries_users_secretary` FOREIGN KEY (`id_users_secretary`) REFERENCES `wb_users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
            ADD CONSTRAINT `secretaries_users_provider` FOREIGN KEY (`id_users_provider`) REFERENCES `wb_users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE');

        $this->db->query('ALTER TABLE `wb_services`
            ADD CONSTRAINT `services_service_categories` FOREIGN KEY (`id_service_categories`) REFERENCES `wb_service_categories` (`id`)
            ON DELETE SET NULL
            ON UPDATE CASCADE');

        $this->db->query('ALTER TABLE `wb_services_providers`
            ADD CONSTRAINT `services_providers_users_provider` FOREIGN KEY (`id_users`) REFERENCES `wb_users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE,
            ADD CONSTRAINT `services_providers_services` FOREIGN KEY (`id_services`) REFERENCES `wb_services` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE');

        $this->db->query('ALTER TABLE `wb_users`
            ADD CONSTRAINT `users_roles` FOREIGN KEY (`id_roles`) REFERENCES `wb_roles` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE');

        $this->db->query('ALTER TABLE `wb_user_settings`
            ADD CONSTRAINT `user_settings_users` FOREIGN KEY (`id_users`) REFERENCES `wb_users` (`id`)
            ON DELETE CASCADE
            ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop table constraints.
        $this->db->query('ALTER TABLE wb_appoinments DROP FOREIGN KEY appointments_services');
        $this->db->query('ALTER TABLE wb_appoinments DROP FOREIGN KEY appointments_users_customer');
        $this->db->query('ALTER TABLE wb_appoinments DROP FOREIGN KEY appointments_users_provider');
        $this->db->query('ALTER TABLE wb_secretaries_providers DROP FOREIGN KEY secretaries_users_secretary');
        $this->db->query('ALTER TABLE wb_secretaries_providers DROP FOREIGN KEY secretaries_users_provider');
        $this->db->query('ALTER TABLE wb_services_providers DROP FOREIGN KEY services_providers_users_provider');
        $this->db->query('ALTER TABLE wb_services_providers DROP FOREIGN KEY services_providers_services');
        $this->db->query('ALTER TABLE wb_services DROP FOREIGN KEY services_service_categories');
        $this->db->query('ALTER TABLE wb_users DROP FOREIGN KEY users_roles');
        $this->db->query('ALTER TABLE wb_user_settings DROP FOREIGN KEY user_settings_users');

        // Add table constraints again.
        $this->db->query('ALTER TABLE `wb_appoinments`
            ADD CONSTRAINT `wb_appoinments_ibfk_2` FOREIGN KEY (`id_users_customer`) REFERENCES `wb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `wb_appoinments_ibfk_3` FOREIGN KEY (`id_services`) REFERENCES `wb_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `wb_appoinments_ibfk_4` FOREIGN KEY (`id_users_provider`) REFERENCES `wb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE');

        $this->db->query('ALTER TABLE `wb_secretaries_providers`
            ADD CONSTRAINT `fk_wb_secretaries_providers_1` FOREIGN KEY (`id_users_secretary`) REFERENCES `wb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `fk_wb_secretaries_providers_2` FOREIGN KEY (`id_users_provider`) REFERENCES `wb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE');

        $this->db->query('ALTER TABLE `wb_services`
            ADD CONSTRAINT `wb_services_ibfk_1` FOREIGN KEY (`id_service_categories`) REFERENCES `wb_service_categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE');

        $this->db->query('ALTER TABLE `wb_services_providers`
            ADD CONSTRAINT `wb_services_providers_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `wb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT `wb_services_providers_ibfk_2` FOREIGN KEY (`id_services`) REFERENCES `wb_services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE');

        $this->db->query('ALTER TABLE `wb_users`
            ADD CONSTRAINT `wb_users_ibfk_1` FOREIGN KEY (`id_roles`) REFERENCES `wb_roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE');

        $this->db->query('ALTER TABLE `wb_user_settings`
            ADD CONSTRAINT `wb_user_settings_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `wb_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE');
    }
}
