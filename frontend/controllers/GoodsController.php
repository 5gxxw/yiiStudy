<?php

namespace frontend\controllers;

class GoodsController extends \yii\web\Controller
{
    public $layout = 'index';

    public $enableCsrfValidation = false;   //关闭csrf验证
    /**
     * 商品页
     */
    public function actionIndex($id)
    {
        //>>1.获取商品信息
        $model = \backend\models\Goods::findOne(['id'=>$id]);

        return $this->render('index',['model'=>$model]);
    }


}
