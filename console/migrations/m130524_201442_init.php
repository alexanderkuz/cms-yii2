<?php

use yii\db\Schema;
use yii\db\Migration;
use yii\rbac\Item;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }


       /* $this->createTable('{{%user_1}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
       */

        //таблица user
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(120)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),

            'status' => "ENUM('NEW', 'ACTIVE','BLOCKED','DELETE')  NOT NULL DEFAULT 'NEW'",
            'sex' => "ENUM('MALE', 'FEMALE')  NOT NULL DEFAULT 'MALE'",

            'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);

        //таблица user_password_reset_token
        $this->createTable('{{%user_password_reset_token}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'token' => $this->string()->notNull()
        ], $tableOptions);
        $this->addForeignKey('user_password_reset_token_user_id_fk', '{{%user_password_reset_token}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        //таблица user_change_email_token
        $this->createTable('{{%user_email_confirm_token}}', [
            'id' => $this->primaryKey(),
            'user_id' =>  $this->integer()->notNull(),
            'old_email' => $this->string(),
            'old_email_token' => $this->string(),
            'old_email_confirm' => $this->smallInteger()->defaultValue(0),
            'new_email' =>  $this->string()->notNull(),
            'new_email_token' => $this->string()->notNull(),
            'new_email_confirm' => $this->smallInteger()->defaultValue(0),
        ], $tableOptions);
        $this->addForeignKey('user_email_confirm_token_user_id_fk', '{{%user_email_confirm_token}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        //таблица user_oauth_key
        $this->createTable('{{%user_oauth_key}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'provider_id' => $this->integer(),
            'provider_user_id' => $this->string(255)
        ], $tableOptions);
        $this->addForeignKey('user_oauth_key_user_id_fk', '{{%user_oauth_key}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        //таблица auth_rule
        $this->createTable('{{%auth_rule}}', [
            'name' => Schema::TYPE_STRING.'(64) NOT NULL',
            'data' => Schema::TYPE_TEXT,
            'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',

        ], $tableOptions);
        $this->addPrimaryKey('auth_rule_pk', '{{%auth_rule}}', 'name');

        //таблица auth_item
        $this->createTable('{{%auth_item}}', [
            'name' => Schema::TYPE_STRING.'(64) NOT NULL',
            'type' => Schema::TYPE_INTEGER.' NOT NULL',
            'description' => Schema::TYPE_TEXT,
            'rule_name' => Schema::TYPE_STRING.'(64)',
            'data' => Schema::TYPE_TEXT,
            'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);
        $this->addPrimaryKey('auth_item_name_pk', '{{%auth_item}}', 'name');
        $this->addForeignKey('auth_item_rule_name_fk', '{{%auth_item}}', 'rule_name', '{{%auth_rule}}',  'name', 'SET NULL', 'CASCADE');
        $this->createIndex('auth_item_type_index', '{{%auth_item}}', 'type');

        //таблица auth_item_child
        $this->createTable('{{%auth_item_child}}', [
            'parent' => Schema::TYPE_STRING.'(64) NOT NULL',
            'child' => Schema::TYPE_STRING.'(64) NOT NULL'
        ], $tableOptions);
        $this->addPrimaryKey('auth_item_child_pk', '{{%auth_item_child}}', array('parent', 'child'));
        $this->addForeignKey('auth_item_child_parent_fk', '{{%auth_item_child}}', 'parent', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('auth_item_child_child_fk', '{{%auth_item_child}}', 'child', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');

        //таблица auth_assignment
        $this->createTable('{{%auth_assignment}}', [
            'item_name' => Schema::TYPE_STRING.'(64) NOT NULL',
            'user_id' => Schema::TYPE_INTEGER.'(11) NOT NULL',
            'created_at' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP',
        ], $tableOptions);
        $this->addPrimaryKey('auth_assignment_pk', '{{%auth_assignment}}', array('item_name', 'user_id'));
        $this->addForeignKey('auth_assignment_item_name_fk', '{{%auth_assignment}}', 'item_name', '{{%auth_item}}', 'name', 'CASCADE', 'CASCADE');
        $this->addForeignKey('auth_assignment_user_id_fk', '{{%auth_assignment}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        //аккаунт для администратора и права
        $this->batchInsert('{{%user}}', ['username', 'auth_key', 'password_hash', 'email', 'status'], [
            [
                'MIGRATION_ADMINISTRATOR',
                Yii::$app->security->generateRandomString(),
                Yii::$app->security->generatePasswordHash('123123'),
                'administrator@example.com',
                'ACTIVE',

            ],
            [
                'MIGRATION_MODERATOR',
                Yii::$app->security->generateRandomString(),
                Yii::$app->security->generatePasswordHash('123123'),
                'moderator@example.com',
                'ACTIVE',

            ]
        ]);
          $this->insert('{{%auth_rule}}', [
              'name' => 'noElderRank',
              'data' => 'O:34:"common\modules\users\rbac\NoElderRankRule":3:{s:4:"name";s:11:"noElderRank";s:9:"createdAt";N;s:9:"updatedAt";i:1431880756;}',
          ]);
        $this->batchInsert('{{%auth_item}}', ['name', 'type', 'description', 'rule_name'], [
            ['administrator', Item::TYPE_ROLE,  'MIGRATION_ADMINISTRATOR', NULL],
            ['moderator', Item::TYPE_ROLE,  'MIGRATION_MODERATOR', NULL],
            ['rbacManage', Item::TYPE_PERMISSION,  'MIGRATION_RBAC_MANAGE', NULL],
            ['userCreate', Item::TYPE_PERMISSION,  'MIGRATION_USER_CREATE', NULL],
            ['userDelete', Item::TYPE_PERMISSION,  'MIGRATION_USER_DELETE', NULL],
            ['userManage', Item::TYPE_PERMISSION,  'MIGRATION_USER_MANAGE', NULL],
            ['userPermissions', Item::TYPE_PERMISSION,  'MIGRATION_USER_PERMISSIONS', NULL],
            ['userUpdate', Item::TYPE_PERMISSION,  'MIGRATION_USER_UPDATE', NULL],
            ['userUpdateNoElderRank', Item::TYPE_PERMISSION,  'MIGRATION_USER_UPDATE_NO_ELDER_RANK', null], //noElderRank
            ['userView', Item::TYPE_PERMISSION,  'MIGRATION_USER_VIEW', NULL],
        ]);
        $this->batchInsert('{{%auth_item_child}}', ['parent', 'child'], [
            ['administrator', 'rbacManage'],
            ['administrator', 'userCreate'],
            ['administrator', 'userDelete'],
            ['administrator', 'userPermissions'],
            ['administrator', 'userUpdate'],
            ['administrator', 'moderator'],
            ['moderator', 'userManage'],
            ['moderator', 'userView'],
            ['moderator', 'userUpdateNoElderRank'],
            ['userUpdateNoElderRank', 'userUpdate'],
        ]);
        $this->batchInsert('{{%auth_assignment}}', ['item_name', 'user_id'], [
            ['administrator', 1],
            ['moderator', 2],
        ]);

    }

    public function down()
    {
        $this->dropTable('{{%auth_assignment}}');
        $this->dropTable('{{%auth_item_child}}');
        $this->dropTable('{{%auth_item}}');
        $this->dropTable('{{%auth_rule}}');
        $this->dropTable('{{%user_oauth_key}}');
        $this->dropTable('{{%user_email_confirm_token}}');
        $this->dropTable('{{%user_password_reset_token}}');
        $this->dropTable('{{%user}}');
    }
}
