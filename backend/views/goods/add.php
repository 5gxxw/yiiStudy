
<h1>商品操作</h1>
<?php
/**
 * 添加商品
 * @var $this \yii\web\View
 */

use yii\web\JsExpression;
use \yii\bootstrap\Html;
use \xj\uploadify\Uploadify;

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');


echo $form->field($model,'logo_file')->fileInput();


//echo $form->field($model,'logo')->hiddenInput();
echo Html::img($model->logo,['style'=>'max-height:100px']);
//外部TAG
//echo Html::fileInput('test', NULL, ['id' => 'test']);
//echo Uploadify::widget([
//    'url' => yii\helpers\Url::to(['s-upload']),
//    'id' => 'test',
//    'csrf' => true,
//    'renderTag' => false,
//    'jsOptions' => [
//        'width' => 120,
//        'height' => 40,
//        'onUploadError' => new JsExpression(<<<EOF
//function(file, errorCode, errorMsg, errorString) {
//    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
//}
//EOF
//        ),
//        'onUploadSuccess' => new JsExpression(<<<EOF
//function(file, data, response) {
//    data = JSON.parse(data);
//    if (data.error) {
//        console.log(data.msg);
//    } else {
////        console.log(data);
//        $('#goods-logo').val(data.fileUrl);
//        $('img').attr('src',data.fileUrl);
//        //上传到服务器后,需要将文件上传到七牛云上
//    }
//}
//EOF
//        ),
//    ]
//]);



/**
 * 商品分类列表
 */
echo $form->field($model,'goods_category_id')->hiddenInput();
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';
$goodsCategory = \yii\helpers\Json::encode(\backend\models\Goods::getGoodsCategoryAll());

//注册js代码
$js = <<<EOD
        var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
            callback: {
                onClick: function(event, treeId, treeNode){
                      $("#goods-goods_category_id").val(treeNode.id); //为parent_id设置值
                }
            }
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes = {$goodsCategory};
        zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
        zTreeObj.expandAll(true);   //展开所有节点
        zTreeObj.selectNode(zTreeObj.getNodeByParam('id','{$model->goods_category_id}'),null);   //选中节点
EOD;
$this->registerJs($js);

echo $form->field($model,'brand_id')->dropDownList(\backend\models\Goods::getBrandAll());
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'is_on_sale',['inline'=>true])->radioList(\backend\models\Goods::$is_on_sale_list);
echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Goods::$status_list);
echo $form->field($model,'sort');

echo $form->field($intro,'content')->widget('kucha\ueditor\UEditor',[]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();


//在jquery后面加载
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]) //给当前视图注册js文件
?>

<link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
