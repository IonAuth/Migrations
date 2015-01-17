<?php

use Phinx\Migration\AbstractMigration;

class IonAuth3 extends AbstractMigration
{

    /**
     * Migrate Up.
     */
    public function up()
    {
      $Groups = $this->table('groups');
      $Groups->addColumn('name', 'string', ['limit' => 20])
        ->addColumn('description', 'string', ['limit' => 100])
        ->save();

      $this->execute("
        INSERT INTO `groups` (`id`, `name`, `description`)
          VALUES
            ('1', 'admin', 'Administrator'),
            ('2', 'members', 'General User');
      ");

      $Users = $this->table('users');
      $Users->addColumn('ip_address', 'string', ['limit' => 15])
        ->addColumn('username', 'string', ['limit' => 100])
        ->addColumn('password', 'string', ['limit' => 255])
        ->addColumn('salt', 'string', ['limit' => 255])
        ->addColumn('email', 'string', ['limit' => 100])
        ->addColumn('activation_code', 'string', ['limit' => 40])
        ->addColumn('forgotten_password_code', 'string', ['limit' => 40])
        ->addColumn('forgotten_password_time', 'integer', ['limit' => 11])
        ->addColumn('remember_code', 'string', ['limit' => 40])
        ->addColumn('created_on', 'integer', ['limit' => 11])
        ->addColumn('last_login', 'integer', ['limit' => 11])
        ->addColumn('active', 'integer', ['limit' => 1])
        ->addColumn('first_name', 'string', ['limit' => 50])
        ->addColumn('last_name', 'string', ['limit' => 50])
        ->addColumn('company', 'string', ['limit' => 100])
        ->addColumn('phone', 'string', ['limit' => 20])
        ->save();

        $this->execute("
          INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`)
            VALUES
              ('1','127.0.0.1','administrator','$2a$07$SeBknntpZror9uyftVopmu61qg0ms8Qv1yV6FG.kQOSM.9QhmTo36','','admin@admin.com','',NULL,'1268889823','1268889823','1', 'Admin','istrator','ADMIN','0');
        ");

        $this->execute("
          CREATE TABLE `users_groups` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `user_id` int(11) unsigned NOT NULL,
          `group_id` mediumint(8) unsigned NOT NULL,
          PRIMARY KEY (`id`),
          KEY `fk_users_groups_users1_idx` (`user_id`),
          KEY `fk_users_groups_groups1_idx` (`group_id`),
          CONSTRAINT `uc_users_groups` UNIQUE (`user_id`, `group_id`),
          CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
          CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
          ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ");

        $this->execute("
          INSERT INTO `users_groups`(`id`, `user_id`, `group_id`)
            VALUES
              (1,1,1),
              ('2',1,2);
        ");

        $Attempts = $this->table('login_attempts');
        $Attemps->addColumn('ip_address', 'string', ['limit' => 15])
          ->addColumn('login', 'string', ['limit' => 100])
          ->addColumn('time', 'integer', ['limit' => 11])
          ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
      $this->dropTable('groups');
      $this->dropTable('users');
      $this->droptable('users_groups');
      $this->dropTable('login_attempts');
    }
}
