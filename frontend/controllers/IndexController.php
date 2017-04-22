<?php
/**
 * 网站首页
 */

namespace frontend\controllers;




use backend\models\Brand;
use backend\models\Goods;
use frontend\models\GoodsCategory;

class IndexController extends \yii\web\Controller
{
    public $layout = 'index';

    /**
     * 网站首页
     * @return string
     */
    public function actionIndex()
    {

        return $this->render('index');
    }

    /**
     * 商品列表页
     */
    public function actionList($id){
        //>>1.根据id查询出数据,
        $model = GoodsCategory::findOne(['id'=>$id]);
        //>>查询出所有下级分类
        $models = GoodsCategory::find()->where(['parent_id'=>$id])->all();
        //>>查询出品牌列表
        $brand = Brand::find()->all();
        //>>查询出所有的商品数据
        $goods = Goods::find()->all();
        return $this->render('list',['models'=>$models,'model'=>$model,'brand'=>$brand,'goods'=>$goods]);
    }

    /**
     * 根据商品分类id获取商品数据
     * @param $id
     */
    public function actionGoodsList($id)
    {
        //>>获取商品列表
        $goods = Goods::find()->where(['goods_category_id'=>$id])->all();


        return $this->render('list',['goods'=>$goods]);
    }

}
