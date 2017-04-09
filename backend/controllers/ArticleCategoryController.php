<?php
/**
 * 文章分类管理
 */
namespace backend\controllers;

use app\models\Article;
use backend\filter\MyAccessFilter;
use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Controller;

class ArticleCategoryController extends Controller
{
    /**
     * 文章分类列表
     * 1.实例化模型
     *
     * @return string
     */
    public function actionIndex()
    {
        $models = ArticleCategory::find()->all();
        return $this->render('index',['models'=>$models]);
    }

    /**
     * 添加分类
     * 1.实例化
     * 2.显示视图
     * 3.判断请求方式
     * 4.自动加载,验证
     * 5.验证成功,保存数据到数据库
     * 6.提示信息,返回列表
     */
    public function actionAdd()
    {
        $model = new ArticleCategory();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->addFlash('success','添加分类成功');
                return $this->redirect(['article-category/index']);
            }
        }

        return $this->render('add',['model'=>$model]);
    }

    /*
     * 修改文章分类
     * 1.根据id查找数据
     * 2.显示修改视图
     * 3.实例化请求组件,判断请求方式
     * 4.加载
     * 5.验证
     * 6.成功后保存
     * 7.提示信息,显示页面
     */
    public function actionEdit($id)
    {
        $model = ArticleCategory::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                \Yii::$app->session->addFlash('success','修改分类成功');
                return $this->redirect(['article-category/index']);
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    /**
     * 删除文章分类
     */
    public function actionDel($id)
    {
        $model = ArticleCategory::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->addFlash('success','删除分类成功');
        return $this->redirect(['article-category/index']);
    }

    /**
     * @param $id
     * 查看分类下的文章
     */
    public function actionArticleList($id){
        $query = Article::find()->where(['=','article_category_id',$id]);
        $total = $query->count();
        $pageSize = 3;
        $pager = new Pagination([
            'totalCount' => $total,
            'pageSize' => $pageSize,
        ]);
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('//article/index',['models'=>$models,'pager'=>$pager]);
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
