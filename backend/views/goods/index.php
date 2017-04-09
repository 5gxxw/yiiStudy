
<h1>商品列表</h1>
<br/>
<?php
/*
* 搜索功能
*/
$form = \yii\bootstrap\ActiveForm::begin([
    'method' => 'get',
    'action' => \yii\helpers\Url::to(['goods/index']),
    'options' => ['class'=>'form-inline'],
]);
echo \yii\bootstrap\Html::a('添加商品',['goods/add'],['class'=>'btn btn-info','style'=>'margin-right:6px;margin-bottom:10px']);
echo \yii\bootstrap\Html::a('回收站',['goods/recycle'],['class'=>'btn btn-danger','style'=>'margin-bottom:10px;margin-right:90px']);
echo $form->field($search,'name')->textInput(['placeholder'=>'商品名称','style'=>'margin-right:6px'])->label('label',['class'=>'sr-only']);
echo $form->field($search,'sn')->textInput(['placeholder'=>'货号','style'=>'margin-right:6px'])->label('label',['class'=>'sr-only']);
echo $form->field($search,'minPrice')->textInput(['placeholder'=>'￥ 最小价格',])->label('label',['class'=>'sr-only']);
echo '－';
echo $form->field($search,'maxPrice')->textInput(['placeholder'=>'￥ 最大价格','style'=>'margin-right:6px'])->label('label',['class'=>'sr-only']);
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-info','style'=>'margin-bottom:10px']);
\yii\bootstrap\ActiveForm::end();


?>
<br/>
<table class="table table-hover table-bordered">
    <tr>
        <th>名称</th>
        <th>货号</th>
        <th>logo</th>
        <th>商品分类</th>
        <th>品牌</th>
        <th>本店价格</th>
        <th>库存</th>
        <th>是否上架</th>
        <th>录入时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=mb_substr($model->name,0,20)?></td>
            <td><?=$model->sn?></td>
            <td><?=\yii\bootstrap\Html::img($model->logoUrl(),['style'=>'height:30px'])?></td>
            <td><?=$model->goodsCategory->name?></td>
            <td><?=$model->brand->name?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td><?=\backend\models\Goods::$is_on_sale_list[$model->is_on_sale]?></td>

            <td><?=date('Y-m-d H:i:s',$model->input_time)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('相册',['goods/gallery','id'=>$model->id],['class'=>'btn btn-info btn-sm'])?>
                <?=\yii\bootstrap\Html::a('编辑',['goods/edit','id'=>$model->id],['class'=>'btn btn-success btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods/del-recycle','id'=>$model->id],['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>

</table>
<?=
    \yii\widgets\LinkPager::widget([
        'pagination' => $pager,
        'nextPageLabel' => '下一页',
        'prevPageLabel' => '上一页',
    ])
?>