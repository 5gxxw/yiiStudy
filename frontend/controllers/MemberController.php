<?php

namespace frontend\controllers;

use frontend\models\Member;
use frontend\models\MemberForm;

class MemberController extends \yii\web\Controller
{
    //>>定义布局文件
    public $layout = 'login';

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 用户注册
     */
    public function actionRegister(){

        //>>1.实例化
        $model = new Member();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //>>调用方法保存数据到数据库
            if ($model->add()){
                \Yii::$app->session->addFlash('success','注册成功');
                return $this->redirect(['member/index']);
            }
        }
        return $this->render('register',['model'=>$model]);
    }

    /**
     * 用户登录
     */
    public function actionLogin(){
        //>>1.实例化
        $model = new MemberForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            //>>调用方法帮我登录
            if($model->memberLogin()){
                //>>登录成功
                \Yii::$app->session->addFlash('success','登录成功');
                return $this->redirect(['member/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    public function actionGuest(){
        var_dump(\Yii::$app->user->isGuest);
    }
}
