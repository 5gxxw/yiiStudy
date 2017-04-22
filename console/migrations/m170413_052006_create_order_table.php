<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m170413_052006_create_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('order', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->notNull()->comment('用户id'),
            'name' => $this->string(20)->notNull()->comment('收货人'),
            'province' => $this->string(50)->notNull()->comment('省'),
            'city' => $this->string(50)->notNull()->comment('市'),
            'area' => $this->string(50)->notNull()->comment('区'),
            'particular' => $this->string(50)->notNull()->comment('详细地址'),
            'tel' => $this->char(11)->notNull()->comment('手机号码'),
            'delivery_id' =>$this->integer()->notNull()->comment('配送方式id'),
            'delivery_name' =>$this->string(30)->notNull()->comment('配送方式名字'),
            'delivery_price' => $this->decimal(9,2)->notNull()->defaultValue(0.00)->comment('运费'),
            'pay_type_id' => $this->smallInteger()->notNull()->defaultValue(1)->comment('支付方式id'),
            'pay_type_name' => $this->string(30)->notNull()->comment('支付方式名称'),
            'price' => $this->decimal(9,2)->notNull()->defaultValue(0.00)->comment('商品金额'),
            'status' => $this->smallInteger(4)->notNull()->defaultValue(0)->comment('订单状态,0取消,1待付款,2待发货,3待收货,4完成'),
            'trade_no' => $this->string(30)->comment('第三方支付交易号'),
            'create_time' =>$this->integer()->comment('创建时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('order');
    }
}
