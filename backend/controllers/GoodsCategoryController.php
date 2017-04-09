<?php

namespace backend\controllers;

use backend\filter\MyAccessFilter;
use backend\models\GoodsCategory;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\helpers\Json;

class GoodsCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $models = GoodsCategory::find()->orderBy('tree,lft')->all();

        return $this->render('index',['models'=>$models]);
    }

    /**
     * 添加商品分类
     * 1.实例化模型
     * 2.视图需要显示上级分类,所有查询出所有分类,并且需要一个顶级分类,才能添加一级分类,所以构建一个数组放入分类变量里面,传入前台
     * 3.显示视图
     * 4.判断请求方式,判断自动加载和验证
     * 5.通过后判断如果父id为0,就创建一级分类,否则创建非一级分类
     * 6.创建非一级分类需要先获取到父分类信息.所有查找父分类,使用prependTo创建分类
     */
    public function actionAdd(){
        $model = new GoodsCategory();
        if(\Yii::$app->request->isPost){
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                if($model->parent_id == 0){
                    $model->makeRoot(); //创建根节点
                }else{
                    //创建非一级分类
                    //查找父分类
                    $parent_cate = GoodsCategory::findOne(['id'=>$model->parent_id]);
                    //传入父分类信息
                    $model->prependTo($parent_cate);
                }
            }
            \Yii::$app->session->addFlash('success','添加成功');
            return $this->redirect(['goods-category/index']);//跳转到当前页
        }
        $models = GoodsCategory::find()->asArray()->all();
        $models[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        $models = Json::encode($models);
        return $this->render('add',['model'=>$model,'models'=>$models]);
    }

    /**
     * 修改商品分类信息
     * 1.根据id获取到商品分类信息
     * 2.视图需要显示上级分类,所有查询出所有分类,并且需要一个顶级分类,才能添加一级分类,所以构建一个数组放入分类变量里面,传入前台
     * 3.显示视图
     * 4.判断请求方式,判断自动加载和验证
     * 5.通过后判断如果父id为0,就创建一级分类,否则创建非一级分类
     * 6.创建非一级分类需要先获取到父分类信息.所有查找父分类,使用prependTo创建分类
     */
    public function actionEdit($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        if(\Yii::$app->request->isPost){
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                try{
                    if($model->parent_id == 0){
                        $model->makeRoot(); //创建根节点
                    }else{
                        //创建非一级分类
                        //查找父分类
                        $parent_cate = GoodsCategory::findOne(['id'=>$model->parent_id]);
                        //传入父分类信息
                        $model->prependTo($parent_cate);
                        \Yii::$app->session->addFlash('success','添加成功');
                        return $this->refresh();//跳转到当前页
                    }
                }catch(Exception $e){
                    //\Yii::$app->session->setFlash('danger',$e->getMessage());
                    $model->addError('parent_id',$e->getMessage());
                }
            }
        }
        $models = GoodsCategory::find()->asArray()->all();
        $models[] = ['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        $models = Json::encode($models);
        return $this->render('add',['model'=>$model,'models'=>$models]);
    }

    /**
     * 删除商品分类
     * 1.根据id查询出数据
     */
    public function actionDel($id){
        $model = GoodsCategory::findOne(['id'=>$id]);

        if($model->children()->count()>0){
            \Yii::$app->session->addFlash('danger','不能删除有下级分类的分类');
            return $this->redirect(['goods-category/index']);
        }
        $model->delete();
        \Yii::$app->session->addFlash('success','删除成功');
        return $this->redirect(['goods-category/index']);
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
