
<h1>回收站列表</h1>

<?=
\yii\bootstrap\Html::a('返回列表',['goods/index'],['class'=>'btn btn-info']);
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
            <td><?=$model->name?></td>
            <td><?=$model->sn?></td>
            <td><?=\yii\bootstrap\Html::img($model->logoUrl(),['style'=>'height:30px'])?></td>
            <td><?=$model->goodsCategory->name?></td>
            <td><?=$model->brand->name?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td><?=\backend\models\Goods::$is_on_sale_list[$model->is_on_sale]?></td>
            <td><?=date('Y-m-d H:i:s',$model->input_time)?></td>
            <td>
                <?=\yii\bootstrap\Html::a('恢复',['goods/restore','id'=>$model->id],['class'=>'btn btn-info btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods/del','id'=>$model->id],['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>

</table>
<?=
    \yii\widgets\LinkPager::widget([
        'pagination' => $pager,
    ])
?>