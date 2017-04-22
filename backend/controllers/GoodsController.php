<?php
/**
 * 商品表
 */
namespace backend\controllers;

use backend\filter\MyAccessFilter;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use backend\models\GoodsSearchForm;
use xj\uploadify\UploadAction;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\UploadedFile;

class GoodsController extends Controller
{
    /**
     * @return string
     * 商品列表
     */
    public function actionIndex()
    {
        //>>搜索功能
        //>>1.创建一个表单模型,实例化表单模型
        $search = new GoodsSearchForm();
        $query = Goods::find()->where(['=','status',1]);//不显示删除的数据
        //>>调用表单模型的搜索方法
        $search->search($query);

        $totalCount = $query -> count();
        $pageSize = 5;
        $pager = new Pagination([
            'totalCount' => $totalCount,
            'pageSize' => $pageSize,
        ]);
        $models = $query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager,'search'=>$search]);
    }

    /**
     * 添加商品
     * 1.实例化商品表对象
     *
     */
    public function actionAdd(){
        //>>实例化商品模型
        $model = new Goods();
        //>>实例化详情模型
        $intro = new GoodsIntro();
        //>>判断请求方式
        $request = \Yii::$app->request;
        if($request->isPost){
            //>>加载商品和详情
            if($model->load($request->post()) && $intro->load($request->post())){
                //>>调用判断商品分类是否是最后一级分类的方法,返回true才执行
                if($model->isCategory()){
                    //>>将上传的文件赋值给上传文件对象
                    $model->logo_file = UploadedFile::getInstance($model,'logo_file');
                    //>>验证商品和详情
                    if($model->validate()){
                        //判断是否上传logo
                        if($model->logo_file){
                            //重命名
                            $fileName = 'upload/goods/'.uniqid().'.'.$model->logo_file->extension;
                            //保存图片到服务器
                            $model->logo_file->saveAs($fileName,false);
                            //保存名称到logo
                            $model->logo = $fileName;
                        }

                        //判断是否有当天的记录
                        $goods_count = GoodsDayCount::findOne(['day'=>date('Ymd')]);
                        if($goods_count == null){
                            //实例化商品数量表对象
                            $goods_count = new GoodsDayCount;
                            //商品时间
                            $goods_count->day = date('Ymd');
                            $goods_count->count = 0;
                            $goods_count->save();
                        }
                        $goods_count->count += 1;
                        $goods_count->save();
                        //生成货号和录入时间
                        /**
                         * sprintf("%07d",变量88)     格式化输出,% 补0 总共7位 d为数字,s为字母
                         * str_pad('1',4,0,STR_PAD_LEFT) 字符串,长度,补0,补左边
                         * $num = strlen($goods::findOne(['day'=>date('Ymd')])->count);
                         * $model->sn = date('Ymd').str_repeat(0,7-$num).$goods::findOne(['day'=>date('Ymd')])->count;
                         */
                        $model->sn = date('Ymd').sprintf("%07d",$goods_count->count);
                        $model->input_time = time();
                        //保存model数据
                        $model->save();
                        //>>商品详情的goods_id
                        $intro->goods_id = $model->id;
                        //>>验证详情
                        if ($intro->validate()){
                            //>>保存详情数据
                            $intro->save();
                            \Yii::$app->session->addFlash('success','添加商品成功');
                            return $this->redirect(['goods/index']);
                        }
                    }else{
                        var_dump($model->getErrors());exit;
                    }
                }else{
                    $model->addError('goods_category_id','只能选择最后一层分类');
                }
            }
        }
        //>>显示视图
        return $this->render('add',['model'=>$model,'intro'=>$intro]);
    }

    /**
     * 编辑商品
     */
    public function actionEdit($id){
        $model = Goods::findOne(['id'=>$id]);
        //>>商品详情对象
        $intro= GoodsIntro::findOne(['goods_id'=>$id]);
        $request = \Yii::$app->request;
        if($request->isPost){
            if($model->load($request->post()) && $intro->load($request->post())){
                //>>调用判断商品分类是否是最后一级分类的方法,返回true才执行
                if($model->isCategory()) {
                    if ($model->validate() && $intro->validate()) {
                        //保存model数据
                        $model->save();
                        //>>保存商品详情数据
                        $intro->save();
                        \Yii::$app->session->addFlash('success', '编辑商品成功');
                        return $this->redirect(['goods/index']);
                    }
                }else{
                    $model->addError('goods_category_id','只能选择最后一层分类');
                }
            }
        }

        return $this->render('add',['model'=>$model,'intro'=>$intro]);
    }

    /**
     * @return array 相册
     */
    public function actionGallery($id){
        $models = GoodsGallery::find()->where(['=','goods_id',$id])->all();
        //实例化相册模型
        $gallery_add = new GoodsGallery();
        if(\Yii::$app->request->isPost){
            //将上传的多文件赋值给上传文件对象
            $gallery_add->path_list = UploadedFile::getInstances($gallery_add,'path_list');
            if($gallery_add->path_list){
                //循环每个文件
                foreach ($gallery_add->path_list as $file) {
                    //重命名
                    $fileName = 'upload/gallery/'.uniqid().$file->name;
                    //保存文件到服务器
                    if($file->saveAs($fileName,false)){
                        $gallery = new GoodsGallery();
                        //保存路径
                        $gallery->path = $fileName;
                        $gallery->goods_id = $id;
                        //保存到数据库
                        $gallery->save();
                    }
                }
            }
            \Yii::$app->session->addFlash('success','添加相册成功');
            return $this->redirect(['goods/gallery','id'=>$id]);
        }
        return $this->render('gallery',['models'=>$models,'gallery_add'=>$gallery_add,'present_id'=>$id]);
    }

    /**
     * 相册删除
     */
    public function actionDelGallery($id,$present_id){
        //实例化相册模型
        $model = GoodsGallery::findOne(['id'=>$id]);
        $model->delete();
        \Yii::$app->session->addFlash('success','删除相册成功');
        return $this->redirect(['goods/gallery','id'=>$present_id]);
    }

    /**
     * @return array
     * 删除->回收站
     */
    public function actionDelRecycle($id){
        //根据id获取到数据
        $model = Goods::findOne(['id'=>$id]);
        //将状态改为0
        $model->status = 0;
        $model->save();
        \Yii::$app->session->addFlash('success','删除商品成功');
        return $this->redirect(['goods/index']);
    }

    /**
     * 回收站
     */
    public function actionRecycle(){
        //>>获取回收站的数据
        $model = Goods::find()->where(['=','status',0]);
        //>>总条数
        $totalCount = $model -> count();
        //>>每页显示的条数
        $pageSize = 5;
        //>>分页
        $pager = new Pagination([
            'totalCount' => $totalCount,
            'pageSize' => $pageSize,
        ]);
        //>>获取需要显示的数据
        $models = $model->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('recycle',['models'=>$models,'pager'=>$pager]);
    }

    /**
     * 回收站恢复
     */
    public function actionRestore($id){
        //根据id获取到数据
        $model = Goods::findOne(['id'=>$id]);
        //将状态改为0
        $model->status = 1;
        $model->save();
        \Yii::$app->session->addFlash('success','商品恢复成功');
        return $this->redirect(['goods/recycle']);
    }

    /**
     * @return array
     * 回收站删除-彻底删除
     */
    public function actionDel($id){
        //根据id获取到数据
        $model = Goods::findOne(['id'=>$id]);
        //>>商品详情数据
        $intro = GoodsIntro::findOne(['goods_id'=>$id]);
        //>>获取商品相册数据
        $galleries = GoodsGallery::find()->where(['=','goods_id',$id])->all();
        //>>删除商品相册图片
        foreach($galleries as $gallery){
            $path = \Yii::getAlias('@webroot/') . $gallery->path;
            unlink($path);
            $gallery->delete();
        }
        //>>删除商品详情
        $intro->delete();
        //>>删除商品数据
        $model->delete();
        \Yii::$app->session->addFlash('success','商品删除成功');
        return $this->redirect(['goods/recycle']);
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ],
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
