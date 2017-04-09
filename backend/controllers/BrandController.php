<?php

namespace backend\controllers;

use backend\filter\MyAccessFilter;
use backend\models\Brand;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;
use crazyfd\qiniu\Qiniu;

class BrandController extends Controller
{
    /**
     * 品牌列表
     * @return string
     * 1.实例化品牌表活动记录
     * 2.取出总条数
     * 3.定义每页显示的条数
     * 4.实例化分页工具类
     * 5.取出数据
     * 6.显示视图,传入分页工具类对象
     */
    public function actionIndex()
    {

        $model = Brand::find()->where(['>','status',-1]);
        $total = $model->count();
        $pageSize = 3;
        $pager = new Pagination([
            'totalCount' => $total,
            'pageSize' => $pageSize,
        ]);

        $models =$model->limit($pager->limit)->offset($pager->offset)->all();

        return $this->render('index',['models'=>$models,'pager' => $pager]);
    }

    /**
     * 添加品牌
     * 1.实例化活动记录
     * 2.显示视图
     * 3.实例化请求组件
     * 4.判断请求方式
     * 5.自动加载
     * 6.验证之前实例化上传文件对象
     * 7.验证之后判断是否上传文件
     * 8.如果上传文件生成文件名,保存文件到服务器,保存文件名到数据库
     * 9.保存数据到数据库
     * 10.提示信息,跳转到列表页
     */
    public function actionAdd()
    {
        $model = new Brand();
        $request = \Yii::$app->request;
        if($request->isPost){
            $model->load($request->post());
//            $model->logo_file = UploadedFile::getInstance($model,'logo_file');
            if($model->validate()) {
//                if ($model->logo_file) {
//                    $fileName = 'upload/brand/' . uniqid() . '.' . $model->logo_file->extension;
//                    $model->logo_file->saveAs($fileName,false);
//                    $model->logo = $fileName;
//                }
                $model->save();
                \Yii::$app->session->addFlash('success', '添加成功');
                return $this->redirect(['brand/index']);
            }
        }
        //默认选中1,正常
        $model->status = 1;
        return $this->render('add',['model'=>$model]);
    }

    /**
     * 编辑品牌
     * 1.实例化brand调用findOne方法,根据id获取品牌信息
     * 2.显示视图
     * 3.实例化请求组件
     * 4.判断请求方式
     * 5.自动加载
     * 6.验证之前,实例化上传文件对象
     * 7.验证表单模型数据
     * 8.如果验证成功,生成logo文件名称
     * 9.保存logo文件到服务器
     * 10.保存logo名称到字段logo
     * 11.保存数据到数据库
     * 12.显示提示信息,跳转到列表页
     */
    public function actionEdit($id)
    {
        $model = Brand::findOne(['id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            if($model->load($request->post())){
                //$model->logo_file = UploadedFile::getInstance($model,'logo_file');
                if($model->validate()){
                    //$fileName = 'upload/brand'.uniqid().'.'.$model->logo_file->extension;
                    //$model->logo_file->saveAs($fileName,false);
                    //$model->logo = $fileName;
                    //>>获取数据库的图片地址
                    //>>根据地址删除七牛云上的图片
                    //>>保存
                    $model->save();
                    \Yii::$app->session->addFlash('success','编辑成功');
                    return $this->redirect(['brand/index']);
                }
            }
        }

        return $this->render('add',['model'=>$model]);
    }

    /**
     *  删除品牌->放入回收站
     * 1.根据id获取品牌信息
     * 2.将状态值改为-1
     * 3.保存的使用需要传入false
     * 3.显示提示信息,跳转到列表页
     */
    public function actionDel($id)
    {
        $model = Brand::findOne(['id'=>$id]);
        $model->status = -1;
        $model->save(false);
        \Yii::$app->session->addFlash('success','已放入回收站');
        return $this->redirect(['brand/index']);
    }

    /**
     * 回收站
     * 1.查询出status为-1的数据
     */
    public function actionRetrieve(){
        $model = Brand::find()->where(['status'=>-1]);
        $total = $model->count();
        $pageSize = 3;
        $pager = new Pagination([
            'totalCount' => $total,
            'pageSize' => $pageSize,
        ]);
        $models =$model->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('retrieve',['models'=>$models,'pager' => $pager]);
    }

    /**
     * 回收站恢复功能
     */
    public function actionRestore($id){
        $model = Brand::findOne(['id'=>$id]);
        $model->status = 1;
        $model->save(false);
        \Yii::$app->session->addFlash('success','恢复成功');
        return $this->redirect(['brand/retrieve']);
    }

    /**
     * 查看简介功能
     * 1.根据id查询出品牌的简介
     * 2.显示视图
     */
    public function actionIntro($id){
        $model = Brand::findOne(['id'=>$id]);
        return $this->render('intro',['model'=>$model]);
    }

    /**
     * 回收站删除功能
     */
    public function actionDelete($id){
        $model = Brand::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->addFlash('success','删除成功');
        return $this->redirect(['brand/retrieve']);
    }

    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload/brand',
                'baseUrl' => '@web/upload/brand',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
//                'format' => [$this, 'methodName'],
//                //END METHOD
//                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
//                'format' => function (UploadAction $action) {
//                    $fileext = $action->uploadfile->getExtension();
//                    $filename = sha1_file($action->uploadfile->tempName);
//                    return "{$filename}.{$fileext}";
//                },
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png','gif0'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //$action->output['fileUrl'] = $action->getWebUrl();
//                    $action->getFilename(); // "image/yyyymmddtimerand.jpg"
//                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
//                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"
                    //将图片上传到七牛云
                    //实例化七牛云组件
                    $qiniu = \Yii::$app->qiniu;
                    //获取文件在本机的路径
                    $action->getSavePath();
                    //需要传入文件路径,文件名
                    $qiniu->uploadFile($action->getSavePath(),$action->getFilename());
                    $url = $qiniu->getLink($action->getFilename()); //获取文件在七牛云上的url地址
                    $action->output['fileUrl'] = $url;  //将七牛云上的地址传给前端js
                },
            ],
        ];
    }

    /**
     * qiniu
     */
    public function actionTest(){

        $qiniu = \Yii::$app->qiniu;

        $key = 'brand58da196d664b8.jpg';    //指定上传到七牛云的文件名
        $fileName = \Yii::getAlias('@webroot').'/upload/brand58da196d664b8.jpg';
        $qiniu->uploadFile($fileName,$key);
        $url = $qiniu->getLink($key);
        return $url;
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
