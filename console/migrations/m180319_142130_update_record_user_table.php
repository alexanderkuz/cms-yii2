<?php

use yii\db\Migration;

/**
 * Class m180319_142130_update_record_user_table
 */
class m180319_142130_update_record_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('user',['username'=>'admin'],['id'=>1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180319_142130_update_record_user_table cannot be reverted.\n";

        return false;
    }


}
