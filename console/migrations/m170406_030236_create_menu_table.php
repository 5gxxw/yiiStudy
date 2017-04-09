<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170406_030236_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(50)->notNull()->defaultValue(0)->comment('上级分类'),
            'name' => $this->string(10)->notNull()->comment('菜单名称'),
            'url' => $this->string(50)->comment('路由'),
            'intro' => $this->text()->comment('描述'),
            'sort' => $this->integer(50)->defaultValue(20)->comment('排序'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
