<?php defined('BASEPATH') OR exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 * @WebScheduler - Web Scheduler
 * @package     @WebScheduler
 * @author      SemeOnline(Pty)Ltd <info@semeonline.co.za>
 * @copyright   Copyright (c) 2019, SemeOnline (Pty)Ltd
 * @link        https://semeonline.co.za
 * @since       v1.8
 * ---------------------------------------------------------------------------- */

class Migration_Change_column_types extends CI_Migration {
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

        // Appointments
        $fields = [
            'id' => [
                'name' => 'id',
                'type' => 'int',
                'constraint' => '11',
                'auto_increment' => TRUE
            ],
            'id_users_provider' => [
                'name' => 'id_users_provider',
                'type' => 'int',
                'constraint' => '11'
            ],
            'id_users_customer' => [
                'name' => 'id_users_customer',
                'type' => 'int',
                'constraint' => '11'
            ],
            'id_services' => [
                'name' => 'id_services',
                'type' => 'int',
                'constraint' => '11'
            ]
        ];

        $this->dbforge->modify_column('wb_appoinments', $fields);

        // Roles
        $fields = [
            'id' => [
                'name' => 'id',
                'type' => 'int',
                'constraint' => '11',
                'auto_increment' => TRUE
            ],
            'appointments' => [
                'name' => 'appointments',
                'type' => 'int',
                'constraint' => '11'
            ],
            'customers' => [
                'name' => 'customers',
                'type' => 'int',
                'constraint' => '11'
            ],
            'services' => [
                'name' => 'services',
                'type' => 'int',
                'constraint' => '11'
            ],
            'users' => [
                'name' => 'users',
                'type' => 'int',
                'constraint' => '11'
            ],
            'system_settings' => [
                'name' => 'system_settings',
                'type' => 'int',
                'constraint' => '11'
            ],
            'user_settings' => [
                'name' => 'user_settings',
                'type' => 'int',
                'constraint' => '11'
            ]
        ];

        $this->dbforge->modify_column('wb_roles', $fields);

        // Secretary Provider
        $fields = [
            'id_users_secretary' => [
                'name' => 'id_users_secretary',
                'type' => 'int',
                'constraint' => '11'
            ],
            'id_users_provider' => [
                'name' => 'id_users_provider',
                'type' => 'int',
                'constraint' => '11'
            ]
        ];

        $this->dbforge->modify_column('wb_secretaries_providers', $fields);

        // Services
        $fields = [
            'id' => [
                'name' => 'id',
                'type' => 'int',
                'constraint' => '11',
                'auto_increment' => TRUE
            ],
            'id_service_categories' => [
                'name' => 'id_service_categories',
                'type' => 'int',
                'constraint' => '11'
            ]
        ];

        $this->dbforge->modify_column('wb_services', $fields);

        // Service Providers
        $fields = [
            'id_users' => [
                'name' => 'id_users',
                'type' => 'int',
                'constraint' => '11'
            ],
            'id_services' => [
                'name' => 'id_services',
                'type' => 'int',
                'constraint' => '11'
            ]
        ];

        $this->dbforge->modify_column('wb_services_providers', $fields);

        // Service Categories
        $fields = [
            'id' => [
                'name' => 'id',
                'type' => 'int',
                'constraint' => '11',
                'auto_increment' => TRUE
            ]
        ];

        $this->dbforge->modify_column('wb_service_categories', $fields);

        // Settings
        $fields = [
            'id' => [
                'name' => 'id',
                'type' => 'int',
                'constraint' => '11',
                'auto_increment' => TRUE
            ]
        ];

        $this->dbforge->modify_column('wb_settings', $fields);

        // Users
        $fields = [
            'id' => [
                'name' => 'id',
                'type' => 'int',
                'constraint' => '11',
                'auto_increment' => TRUE
            ],
            'id_roles' => [
                'name' => 'id_roles',
                'type' => 'int',
                'constraint' => '11'
            ]
        ];

        $this->dbforge->modify_column('wb_users', $fields);

        // Users Settings
        $fields = [
            'id_users' => [
                'name' => 'id_users',
                'type' => 'int',
                'constraint' => '11'
            ]
        ];

        $this->dbforge->modify_column('wb_user_settings', $fields);

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

        // Change charset of wb_secretaries_providers table for databases created with EA! 1.2.1 version
        $this->db->query('ALTER TABLE wb_secretaries_providers CONVERT TO CHARACTER SET utf8');
    }

    public function down()
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

        // Appointments
        $fields = [
            'id' => [
                'name' => 'id',
                'type' => 'bigint',
                'constraint' => '20',
                'auto_increment' => TRUE
            ],
            'id_users_provider' => [
                'name' => 'id_users_provider',
                'type' => 'bigint',
                'constraint' => '20'
            ],
            'id_users_customer' => [
                'name' => 'id_users_customer',
                'type' => 'bigint',
                'constraint' => '20'
            ],
            'id_services' => [
                'name' => 'id_services',
                'type' => 'bigint',
                'constraint' => '20'
            ]
        ];

        $this->dbforge->modify_column('wb_appoinments', $fields);

        // Roles
        $fields = [
            'id' => [
                'name' => 'id',
                'type' => 'bigint',
                'constraint' => '20',
                'auto_increment' => TRUE
            ],
            'appointments' => [
                'name' => 'appointments',
                'type' => 'bigint',
                'constraint' => '20'
            ],
            'customers' => [
                'name' => 'customers',
                'type' => 'bigint',
                'constraint' => '20'
            ],
            'services' => [
                'name' => 'services',
                'type' => 'bigint',
                'constraint' => '20'
            ],
            'users' => [
                'name' => 'users',
                'type' => 'bigint',
                'constraint' => '20'
            ],
            'system_settings' => [
                'name' => 'system_settings',
                'type' => 'bigint',
                'constraint' => '20'
            ],
            'user_settings' => [
                'name' => 'user_settings',
                'type' => 'bigint',
                'constraint' => '20'
            ]
        ];

        $this->dbforge->modify_column('wb_roles', $fields);

        // Secretary Provider
        $fields = [
            'id_users_secretary' => [
                'name' => 'id_users_secretary',
                'type' => 'bigint',
                'constraint' => '20'
            ],
            'id_users_provider' => [
                'name' => 'id_users_provider',
                'type' => 'bigint',
                'constraint' => '20'
            ]
        ];

        $this->dbforge->modify_column('wb_roles', $fields);

        // Services
        $fields = [
            'id' => [
                'name' => 'id',
                'type' => 'bigint',
                'constraint' => '20',
                'auto_increment' => TRUE
            ],
            'id_service_categories' => [
                'name' => 'id_service_categories',
                'type' => 'bigint',
                'constraint' => '20'
            ]
        ];

        $this->dbforge->modify_column('wb_services', $fields);

        // Service Providers
        $fields = [
            'id_users' => [
                'name' => 'id_users',
                'type' => 'bigint',
                'constraint' => '20'
            ],
            'id_services' => [
                'name' => 'id_services',
                'type' => 'bigint',
                'constraint' => '20'
            ]
        ];

        $this->dbforge->modify_column('wb_services_providers', $fields);

        // Service Categories
        $fields = [
            'id' => [
                'name' => 'id',
                'type' => 'bigint',
                'constraint' => '20',
                'auto_increment' => TRUE
            ]
        ];

        $this->dbforge->modify_column('wb_service_categories', $fields);

        // Settings
        $fields = [
            'id' => [
                'name' => 'id',
                'type' => 'bigint',
                'constraint' => '20',
                'auto_increment' => TRUE
            ]
        ];

        $this->dbforge->modify_column('wb_settings', $fields);

        // Users
        $fields = [
            'id' => [
                'name' => 'id',
                'type' => 'bigint',
                'constraint' => '20',
                'auto_increment' => TRUE
            ],
            'id_roles' => [
                'name' => 'id',
                'type' => 'bigint',
                'constraint' => '20'
            ]
        ];

        $this->dbforge->modify_column('wb_users', $fields);

        // Users Settings
        $fields = [
            'id_users' => [
                'name' => 'id_users',
                'type' => 'bigint',
                'constraint' => '20'
            ]
        ];

        $this->dbforge->modify_column('wb_user_settings', $fields);

        // Add database constraints.
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
