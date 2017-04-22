<?php

namespace frontend\models;

use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "order".
 *
 * @property integer $id
 * @property integer $member_id
 * @property string $name
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $particular
 * @property string $tel
 * @property integer $delivery_id
 * @property string $delivery_name
 * @property string $delivery_price
 * @property integer $pay_type_id
 * @property string $pay_type_name
 * @property string $price
 * @property integer $status
 * @property string $trade_no
 * @property integer $create_time
 */
class Order extends \yii\db\ActiveRecord
{
    //>>送货方式
    public static $express = [
        1=>['顺丰快递',15,'每张订单不满499.00元,运费15.00元, 订单4...'],
        2=>['中通快递',10,'每张订单不满499.00元,运费15.00元, 订单4...'],
        3=>['申通快递',10,'每张订单不满499.00元,运费15.00元, 订单4...'],
        4=>['韵达快递',12,'每张订单不满499.00元,运费15.00元, 订单4...'],
    ];

    //>>支付方式
    public static $pay = [
        1=>['货到付款'],
        2=>['支付宝'],
        3=>['微信支付'],
        4=>['网商银行'],
    ];


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['member_id', 'name', 'province', 'city', 'area', 'particular', 'tel', 'delivery_id', 'delivery_name', 'pay_type_name'], 'required'],
            [['member_id', 'delivery_id', 'pay_type_id', 'status', 'create_time'], 'integer'],
            [['delivery_price', 'price'], 'number'],
            [['name'], 'string', 'max' => 20],
            [['province', 'city', 'area', 'particular'], 'string', 'max' => 50],
            [['tel'], 'string', 'max' => 11],
            [['delivery_name', 'pay_type_name', 'trade_no'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户id',
            'name' => '收货人',
            'province' => '省',
            'city' => '市',
            'area' => '区',
            'particular' => '详细地址',
            'tel' => '手机号码',
            'delivery_id' => '配送方式id',
            'delivery_name' => '配送方式名字',
            'delivery_price' => '运费',
            'pay_type_id' => '支付方式id',
            'pay_type_name' => '支付方式名称',
            'price' => '商品金额',
            'status' => '订单状态,0取消,1待付款,2待发货,3待收货,4完成',
            'trade_no' => '第三方支付交易号',
            'create_time' => '创建时间',
        ];
    }


    /**
     * @param $carts    .用户的购物车数据,回显到页面的商品清单
     * @return array
     */
    public function OrderGoods($carts){

        //>>保存数据商品清单
        $models = [];
        //>>保存总金额
        $total_price = 0;
        //>>查询出商品名称,logo,价格
        foreach($carts as $cart){
            foreach($cart->goods as $good){
                $model['goods_id'] = $good->id;
                $model['name'] = $good->name;
                $model['logo'] = $good->logo;
                $model['price'] = $good->shop_price;
                $model['num'] = $cart->num;
                $model['subtotal'] = $model['price']*$model['num'];
                $models[] = $model;
                $total_price += $model['subtotal'];
            }
        }
        $data = ['models'=>$models,'total_price'=>$total_price];
        return $data;
    }

    /**
     *
     * @param $data
     * @return $this
     * @throws Exception
     */
    public function Create($data){
        //>>根据收货人信息id获取收货人,省市县,详细地址,手机号
        $site = Site::findOne(['id'=>$data['address']]);
        $this->name = $site->name;
        $this->province = $site->province;
        $this->city = $site->city;
        $this->area = $site->area;
        $this->particular = $site->particular;
        $this->tel = $site->tel;
        //>>根据送货方式获取送货方式id,名称,运费
        $express = Order::$express[$data['delivery']];
        $this->delivery_id = $data['delivery'];
        $this->delivery_name = $express[0];
        $this->delivery_price = $express[1];
        //>>根据支付方式id获取支付方式名称
        $pay = Order::$pay[$data['pay']];
        $this->pay_type_id = $data['pay'];
        $this->pay_type_name = $pay[0];
        //>>计算出商品总金额,
        //>>查询出购物车数据
        $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
        $total_price = 0;
        foreach($carts as $cart){
            $good = Goods::findOne(['id'=>$cart->goods_id]);

            $total_price += $good->shop_price * $cart->num;
        }
        $this->price = $total_price-$express[1];
        //>>用户id,订单状态,创建时间
        $this->member_id = \Yii::$app->user->id;
        $this->create_time = time();
        if($data['pay'] == 1){
            $this->status = 2;
        }else{
            $this->status = 1;
        }

        return $this;
    }
}
