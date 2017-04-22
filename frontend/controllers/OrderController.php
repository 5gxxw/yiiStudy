<?php

namespace frontend\controllers;

use backend\filter\MyAccessFilter;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\Order;
use frontend\models\OrderDetail;
use frontend\models\Site;
use yii\db\Exception;
use yii\filters\AccessControl;

class OrderController extends \yii\web\Controller
{

    public $layout = 'cart';
    public $enableCsrfValidation = false;   //关闭csrf验证


    /**
     * @return string
     * 结算,到生成订单的页面
     */
    public function actionOrder(){
        //>>获取用户的所有收货地址
        $sites = Site::find()->where(['user_id'=>\Yii::$app->user->id])->all();
        //>>获取购物车数据
        $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();

        $order = new Order;
        $data = $order->OrderGoods($carts);
        $models = $data['models'];
        $total_price = $data['total_price'];

        return $this->render('order',['sites'=>$sites,'models'=>$models,'total_price'=>$total_price]);
    }

    /**
     * 保存订单表和订单详情表
     */
    public function actionSave(){
        //>>获取post表单提交的数据
        $data = \Yii::$app->request->post();

        //>>实例化订单模型
        $order = new Order();
        //>>调用模型方法生成订单信息
        $order->create($data);

        //>>开启事务
        $db = \Yii::$app->db;
        $transaction = $db->beginTransaction();
        try{
            //>>保存订单
            $order->save();

            //>>实例化订单详情模型
            $orderDetail = new OrderDetail();
            //>>保存订单详情
            $orderDetail->saveDetail($order->id);

            //>>提交事务
            $transaction->commit();
            //>>成功
            return $this->redirect(['order/success']);
        }catch(Exception $e){
            //>>回滚
            $transaction->rollBack();
            //throw $e;
            //>>失败,返回之前的页面
            return $this->redirect(['cart/carts']);
        }
    }

    public function actionSuccess()
    {
        return $this->render('success');
    }

    public function behaviors(){
        return [
            'accessFilter' => [
                'class' => AccessControl::className(),
                'only' => ['order'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['order'],
                        'roles' => ['@'],
                    ]
                ]
            ]
        ];
    }
}
