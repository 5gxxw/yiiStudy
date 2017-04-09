<?php

namespace backend\controllers;

use backend\filter\MyAccessFilter;
use backend\models\Admin;
use backend\models\LoginForm;
use yii\filters\AccessControl;
use yii\web\Controller;

class AdminController extends Controller
{
    /**
     * 管理员列表
     * @return string
     */
    public function actionIndex()
    {
        $models = Admin::find()->all();
        return $this->render('index',['models'=>$models]);
    }

    /**
     * 登录
     * 1.显示登录表单
     * 2.接受用户填入的数据
     */
    public function actionLogin(){
        $model = new LoginForm();
        $request = \Yii::$app->request;
        if($request->isPost && $model->load($request->post())){
            //>>验证用户名和密码,登录
            if($model->login()){
                //登录成功
                \Yii::$app->session->addFlash('success','登录成功');
                return $this->redirect(['admin/index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    /**
     * 注册
     */
    public function actionAdd(){
        //>>实例化模型
        $model = new Admin();
        //>>指定场景
        $model->scenario = $model::SCENARIO_ADD;
        $request = \Yii::$app->request;
        if($request->isPost && $model->load($request->post())){
            if($model->validate()){
                //>>调用一个方法帮我保存用户注册信息
                if ($model->saveLog()){
                    //>>判断是否添加角色
                    if ($model->roles){
                        //>>保存注册用户角色
                        $model->saveRole();
                    }
                }
                //>>注册完成自动帮用户登录
                //\Yii::$app->user->login($model);
                \Yii::$app->session->addFlash('success','注册成功');
                return $this->redirect(['admin/index']);
            }
        }
        //>>显示视图
        return $this->render('add',['model'=>$model]);
    }


    /**
     * 注销
     */
    public function actionLogout(){
        \Yii::$app->user->logout();
        \Yii::$app->session->addFlash('success','注销成功');
        return $this->redirect(['admin/login']);
    }

    /**
     * 编辑
     */
    public function actionEdit($id){
        $model = Admin::findOne(['id'=>$id]);
        //>>实例化rbac
        $authManager = \Yii::$app->authManager;
        $request = \Yii::$app->request;
        if($request->isPost && $model->load($request->post())){
            if($model->validate()){
                //>>调用方法保存用户数据
                $model->saveLog();
                //>>删除旧的角色
                $authManager->revokeAll($model->id);
                //>>如果有新的角色,就保存
                if($model->roles){
                    //>>调用saveRole保存用户角色
                    $model->saveRole();
                }
                \Yii::$app->session->addFlash('success','修改成功');
                return $this->redirect(['admin/index']);
            }
        }
        //>>调用方法帮我获取旧的用户角色
        $model->getOldRole();
        return $this->render('add',['model'=>$model]);
    }

    /**
     * 删除
     */
    public function actionDel($id){
        $model = Admin::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->addFlash('success','删除成功');
        return $this->redirect(['admin/index']);
    }



    public function actionGuest()
    {
        //可以通过 Yii::$app->user 获得一个 User实例，
        $user = \Yii::$app->user;

        // 当前用户的身份实例。未认证用户则为 Null 。
        $identity = \Yii::$app->user->identity;
        var_dump($identity);
        // 当前用户的ID。 未认证用户则为 Null 。
        $id = \Yii::$app->user->id;
//        var_dump($id);
        // 判断当前用户是否是游客（未认证的）
        $isGuest = \Yii::$app->user->isGuest;
       // var_dump($isGuest);
    }

    /**
     * @return array
     * 权限控制
     */
    public function behaviors(){
        return [
            'accessFilter' => [
                //>>使用自定义的过滤器
                'class' => MyAccessFilter::className(),
                'only' => ['index','add','del','edit'],
            ]
        ];
    }
}
