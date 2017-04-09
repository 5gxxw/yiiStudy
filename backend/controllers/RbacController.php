<?php
/**
 * RBAC权限控制
 */
namespace backend\controllers;

use backend\filter\MyAccessFilter;
use backend\models\PermissionForm;
use backend\models\RolesForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class RbacController extends Controller
{
    /**
     * @return string
     * 权限列表
     */
    public function actionPermissionIndex()
    {
        //>>实例化
        $authManager = \Yii::$app->authManager;
        //>>获取所有权限
        $permissions = $authManager->getPermissions();
        return $this->render('permission-index',['permissions'=>$permissions]);
    }

    /**
     * 创建权限
     * 1.创建表单模型,实例化表单模型,显示视图,模型中需要自定义验证规则
     * 2.表单提交验证成功之后,
     */
    public function actionPermissionAdd(){
        //>>1.实例化表单模型
        $model = new PermissionForm();

        //>>3.添加权限
        if ($model->load(\Yii::$app->request->post()) && $model->validate())
        {
            //>>调用方法保存权限
           $model->add();
            //>>3.3添加成功
            \Yii::$app->session->setFlash('success',$model->description.'权限添加成功');
            return $this->redirect(['permission-index']);
        }

        //>>2.显示示图
        return $this->render('permission-add',['model'=>$model]);
    }

    /*
     * 删除权限
     */
    public function actionPermissionDel($name){
        //>>1.实例化
        $authManager = \Yii::$app->authManager;
        //>>2.获取权限
        $permission = $authManager->getPermission($name);
        //>>3.删除权限
        if($authManager->remove($permission)){
            \Yii::$app->session->setFlash('success',$permission->description.'权限删除成功');
            return $this->redirect(['permission-index']);
        }
    }

    /**
     * 创建角色
     */
    public function actionRolesAdd(){
        //>>1.实例化角色表单模型
        $model = new RolesForm();
        //>>指定场景,才能验证到name
        $model->scenario = RolesForm::SCENARIO_ADD;
        //>>3.添加角色
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //>>调用一个方法帮我添加角色
            if($model->save()){
                \Yii::$app->session->setFlash('success',$model->description.'角色添加成功');
                return $this->redirect(['roles-index']);
            }
        }
        //>>2.显示视图
        return $this->render('roles-add',['model'=>$model]);
    }

    /**
     * 角色列表
     */
    public function actionRolesIndex(){
        $authManager = \Yii::$app->authManager;
        //>>1.获取所有角色
        $roles = $authManager->getRoles();
        //>>2.显示视图
        return $this->render('roles-index',['roles'=>$roles]);
    }

    /**
     * 编辑角色
     */
    public function actionRolesEdit($name){
        //>>1.实例化角色表单模型和rbac权限控制
        $model = new RolesForm();
        $authManager = \Yii::$app->authManager;

        //>>2.获取要修改的角色
        $role = $authManager->getRole($name);
        //>>如果没有该角色,则抛出一个异常
        if ($role == null){
            throw new NotFoundHttpException('角色名不存在');
        }
        //>>调用方法加载数据到表单模型
        $model->loadFormRole($role);

        //>>5.编辑角色
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //>>调用方法编辑角色
            $model->save($role);
            \Yii::$app->session->setFlash('success',$model->description.'角色更新成功');
            return $this->redirect(['roles-index']);
        }
        //>>4.显示视图
        return $this->render('roles-add',['model'=>$model]);
    }

    /*
     * 删除角色
     */
    public function actionRolesDel($name){
        //>>1.实例化
        $authManager = \Yii::$app->authManager;
        //>>2.获取角色
        $role = $authManager->getRole($name);
        //>>3.删除角色(自动会删除权限)
        if($authManager->remove($role)){
            \Yii::$app->session->setFlash('success',$name.'角色删除成功');
            return $this->redirect(['roles-index']);
        }
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
            ]
        ];
    }
}
