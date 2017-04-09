<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m170329_045007_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->comment('名称'),
            'article_category_id' => $this->integer(6)->notNull()->defaultValue(0)->comment('文章分类id'),
            'intro' => $this->text()->comment('简介'),
            'status' => $this->integer(4)->notNull()->defaultValue(1)->comment('状态'),
            'sort' => $this->integer(4)->notNull()->defaultValue(20)->comment('排序'),
            'input_time' => $this->integer(10)->notNull()->defaultValue(0)->comment('录入时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
