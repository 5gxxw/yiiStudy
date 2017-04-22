<?php

use yii\db\Migration;

/**
 * Handles the creation of table `site`.
 */
class m170410_013320_create_site_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('site', [
            'id' => $this->primaryKey(),
            'name' => $this->string(20)->notNull()->comment('收货人'),
            'user_id' => $this->integer()->notNull()->comment('用户id'),
            'province' => $this->string(50)->notNull()->comment('省'),
            'city' => $this->string(50)->notNull()->comment('市'),
            'area' => $this->string(50)->notNull()->comment('区'),
            'particular' => $this->string(50)->notNull()->comment('详细地址'),
            'tel' => $this->char(11)->notNull()->comment('手机号码'),
            'status' => $this->smallInteger(2)->comment('是否为默认地址'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('site');
    }
}
