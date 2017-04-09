<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m170330_024337_create_goods_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->comment('名称'),
            'tree' => $this->integer()->notNull()->comment('树'),
            'lft' => $this->integer()->notNull()->comment('左'),
            'rgt' => $this->integer()->notNull()->comment('右'),
            'depth' => $this->integer()->notNull()->comment('深度'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_category');
    }
}
