<?php
/**
 * 收货地址控制器
 */

namespace frontend\controllers;

use frontend\models\Site;

class DeliveryController extends \yii\web\Controller
{
    public $layout = 'user';

    public function actionIndex()
    {
        $models = Site::find()->all();

        return $this->render('index',['models'=>$models]);
    }

    /**
     * 添加收货地址
     */
    public function actionAdd(){
        //>>1.实例化模型
        $model = new Site();
        if ($model->load(\Yii::$app->request->post())){
            //>>添加用户id
            $model->user_id = \Yii::$app->user->id;
            //>>3.调用方法保存收货地址
            $model->DeliverySave();
            \Yii::$app->session->addFlash('success','添加收货地址成功');
            return $this->redirect(['delivery/add']);
        }
        //>>2.读取收货地址数据显示到页面
//        $models = Site::find()->all();
        return $this->render('add',['model'=>$model]);
    }

    /**
     * 修改收货地址
     */
    public function actionEdit($id){
        //>>1.实例化模型
        $model = Site::findOne(['id'=>$id]);
        if ($model->load(\Yii::$app->request->post())){
            //>>添加用户id
            $model->user_id = \Yii::$app->user->id;
            //>>3.调用方法保存收货地址
            $model->DeliverySave();
            \Yii::$app->session->addFlash('success','修改收货地址成功');
            return $this->redirect(['delivery/index']);
        }
        //>>2.读取收货地址数据显示到页面
        $models = Site::find()->all();
        return $this->render('add',['model'=>$model,'models'=>$models]);
    }

    /**
     * 删除收货地址
     */
    public function actionDel($id){
        $model = Site::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->addFlash('success','删除收货地址成功');
        return $this->redirect(['delivery/index']);
    }

    /**
     * 修改默认地址
     */
    public function actionDefault($id){
        //>>获取用户信息
        $model = Site::findOne(['id'=>$id]);
        //>>调用方法修改默认地址
        if ($model->defaultDelivery()){
            \Yii::$app->session->addFlash('success','设置默认地址成功');
            return $this->redirect(['delivery/index']);
        }
    }

}
