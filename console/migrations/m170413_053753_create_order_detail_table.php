<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order_detail`.
 */
class m170413_053753_create_order_detail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order_detail', [
            'id' => $this->primaryKey(),
            'order_id' =>$this->integer()->notNull()->comment('订单id'),
            'goods_id' =>$this->integer()->notNull()->comment('商品id'),
            'goods_name' => $this->string()->notNull()->comment('商品名称'),
            'goods_logo' => $this->string()->notNull()->comment('商品logo'),
            'price' => $this->decimal(9,2)->notNull()->defaultValue(0.00)->comment('价格'),
            'num' => $this->integer()->notNull()->defaultValue(0)->comment('数量'),
            'total_price' => $this->decimal(9,2)->notNull()->defaultValue(0.00)->comment('小计'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order_detail');
    }
}
