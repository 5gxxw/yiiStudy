<?php

namespace backend\controllers;

use backend\filter\MyAccessFilter;
use backend\models\Menu;
use yii\web\Controller;

class MenuController extends Controller
{
    /**
     * @return string
     * 菜单列表
     */
    public function actionIndex()
    {
        $models = Menu::find()->all();

        return $this->render('index',['models'=>$models]);
    }

    /**
     * 添加菜单
     */
    public function actionAdd()
    {
        //>>1.实例化模型
        $model = new Menu();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //>>3.保存数据
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect(['menu/index']);
        }
        //>>2.调用视图显示
        return $this->render('add',['model'=>$model]);
    }

    /*
     * 修改菜单
     */
    public function actionEdit($id)
    {
        //>>1.根据id获取当前菜单数据
        $model = Menu::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //>>2.根据当前菜单的id判断是否有下级分类
            $parent = Menu::findOne(['parent_id'=>$id]);
            //>如果有下级分类,判断当前parent_id是否为顶级分类
            if($parent && $model->parent_id){
                \Yii::$app->session->setFlash('success','该菜单下有下级分类,只能放在顶级分类下');
                return $this->redirect(['menu/edit','id'=>$id]);
            }
            //>>3.保存数据
            $model->save();
            \Yii::$app->session->setFlash('success','修改成功');
            return $this->redirect(['menu/index']);
        }
        //>>2.显示视图
        return $this->render('add',['model'=>$model]);
    }

    /**
     * 删除菜单
     */
    public function actionDel($id)
    {
        //>>1.根据id获取当前菜单数据
        $model = Menu::findOne(['id'=>$id]);
        //>>2.判断是否有下级分类
        $parent = Menu::findOne(['parent_id'=>$id]);
        if($parent){
            \Yii::$app->session->setFlash('success','该菜单下有下级分类,不能被删除');
            return $this->redirect(['menu/index']);
        }
        $model->delete();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['menu/index']);
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
