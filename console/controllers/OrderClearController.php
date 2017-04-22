<?php

/**
 * 订单超时未支付清理
 */
namespace console\controllers;

use frontend\models\Goods;
use \frontend\models\Order;
use frontend\models\OrderDetail;
use yii\helpers\ArrayHelper;

class OrderClearController extends \yii\console\Controller
{
    public function actionClear()
    {
        while(true){
            //>>1.设置超时时间为不限制
            set_time_limit(0);

            //>>2.获取订单状态为1且时间超过一小时的订单id
            $order = Order::find()->select('id')->where(['status'=>1])->andWhere(['<','create_time',time()-3600])->asArray()->all();
            //>>订单格式化
            $ids = ArrayHelper::map($order,'id','id');

            //>>3.修改订单状态为已取消
            Order::updateAll(['status'=>0],'status=1 AND create_time<'.time()-3600);

            //>>4.返库存
            foreach($ids as $id){
                //>>根据订单id查询出订单详情(商品清单)数据
                $details = OrderDetail::find()->where(['order_id'=>$id])->all();
                foreach($details as $detail){
                    //>>根据商品清单的goods_id查询出商品,并将库存返回
                    Goods::updateAllCounters(['stock'=>$detail->num],'id='.$detail->goods_id);
                }
            }
            //>>设置间隔时间
            sleep(1);
        }
    }
}