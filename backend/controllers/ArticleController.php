<?php
/**
 * 文章管理
 */
namespace backend\controllers;

use backend\models\Article;
use backend\models\ArticleDetail;
use backend\filter\MyAccessFilter;
use yii\data\Pagination;
use yii\web\Controller;


class ArticleController extends Controller
{
    /**
     * 文章管理列表
     * @return string
     * 1.实例化文章模型,查询出总条数,自定义每页显示条数
     * 2.实例化分页工具类,传入总条数和每页显示的条数
     * 3.查询出每页显示的数据
     * 4.显示视图
     */
    public function actionIndex()
    {
        $query = Article::find();
        $total = $query->count();
        $pageSize = 3;
        $pager = new Pagination([
            'totalCount' => $total,
            'pageSize' => $pageSize,
        ]);
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['pager'=>$pager,'models'=>$models]);
    }

    /**
     * 添加文章管理
     * 1.实例化文章模型和文章内容模型
     * 2.显示添加视图
     * 3.实例化请求组件
     * 4.判断请求方式
     * 5.加载成功,并且验证成功
     * 6.添加时间
     * 7.保存文章
     * 8.获取添加的文章id,赋值给文章内容的article_id
     * 9.保存文章内容
     * 10.提示信息,跳转
     */
    public function actionAdd()
    {
        $model = new Article();
        //实例化文章内容模型
        $detail = new ArticleDetail();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
            $detail->load($request->post());
            if($model && $detail){
                if($model->validate() && $detail->validate()){
                    $model->input_time = time();
                    $model->save();
                    //获取最后一条插入的文章内容id
                    //$id = \Yii::$app->db->getLastInsertID();
                    $detail->article_id = $model->id;
                    $detail->save();
                    \Yii::$app->session->addFlash('success','添加成功');
                    return $this->redirect(['article/index']);
                }else{
                    \Yii::$app->session->addFlash('danger','验证失败');
                }
            }else{
                //加载失败
                $model->getErrors();
            }
        }
        return $this->render('add',['model'=>$model,'detail'=>$detail]);
    }

    /**
     * 编辑文章
     * 1.根据id获取文章表和文章内容表的数据,
     * 2.显示视图页面
     * 3.
     */
    public function actionEdit($id){
        $model = Article::findOne(['id'=>$id]);
        $detail = ArticleDetail::findOne(['article_id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            if($model->load($request->post()) &&  $detail->load($request->post())){
                if($model->validate() && $detail->validate()){
                    //验证成功
                    $model->save();
//                    $detail->article_id = $model->id;
                    $detail->save();
                    \Yii::$app->session->addFlash('success','编辑成功');
                    return $this->redirect(['article/index']);
                }else{
                    return \Yii::$app->session->addFlash('danger','验证失败');
                }
            }else{
                return \Yii::$app->session->addFlash('danger','加载模型失败');
            }
        }
        return $this->render('add',['model'=>$model,'detail'=>$detail]);
    }

    /**
     * 删除文章
     */
    public function actionDel($id){
        $model = Article::findOne(['id'=>$id]);
        $detail = ArticleDetail::findOne(['article_id'=>$id]);
        $detail->delete();
        $model->delete();
        \Yii::$app->session->addFlash('success','删除成功');
        return $this->redirect(['article/index']);
    }

    /**
     * 查看文章
     */
    public function actionContent($id){
        $model = Article::findOne(['id'=>$id]);
        $detail = ArticleDetail::findOne(['article_id'=>$id]);
        return $this->render('content',['detail'=>$detail,'model'=>$model]);
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
