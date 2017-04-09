<?php

use yii\db\Migration;

/**
 * Handles the creation of table `brand`.
 */
class m170328_035622_create_brand_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('brand', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->comment('品牌名称'),
            'intro' => $this->text()->comment('简介'),
            'logo' => $this->string(100)->notNull()->comment('LOGO'),
            'sort' => $this->integer()->comment('排序'),
            'status' => $this->integer()->comment('状态'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('brand');
    }
}
