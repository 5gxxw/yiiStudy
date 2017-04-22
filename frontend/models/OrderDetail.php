<?php

namespace frontend\models;

use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "order_detail".
 *
 * @property integer $id
 * @property integer $order_id
 * @property integer $goods_id
 * @property string $goods_name
 * @property string $goods_logo
 * @property string $price
 * @property integer $num
 * @property string $total_price
 */
class OrderDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'goods_id', 'goods_name', 'goods_logo'], 'required'],
            [['order_id', 'goods_id', 'num'], 'integer'],
            [['price', 'total_price'], 'number'],
            [['goods_name', 'goods_logo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单id',
            'goods_id' => '商品id',
            'goods_name' => '商品名称',
            'goods_logo' => '商品logo',
            'price' => '价格',
            'num' => '数量',
            'total_price' => '小计',
        ];
    }


    public function saveDetail($order_id){
        $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        //>>订单模型,一个订单有多个商品,从购物车读取出商品id
        foreach($carts as $cart){
            $good = Goods::findOne(['id'=>$cart->goods_id]);
            //>>检测是否有库存
            if ($cart->num > $good->stock){
                throw new Exception('商品库存不足');
            }
            //订单详情表保存商品id,商品名称,商品logo,价格,数量
            $this->goods_id = $good->id;
            $this->goods_name = $good->name;
            $this->goods_logo = $good->logo;
            $this->price = $good->shop_price;
            $this->num = $cart->num;
            $this->total_price = $cart->num * $good->shop_price;
            //>>获取订单id
            $this->order_id = $order_id;
            //>>保存订单详情
            $this->save();
            //>>商品库存减num
            $good->stock -= $cart->num;
            $good->save();
            //>>清空购物车
            $cart->delete();
        }

    }
}
