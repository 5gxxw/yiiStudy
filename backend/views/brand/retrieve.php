<!--品牌回收站-->
<h1>品牌回收站</h1>
<?=\yii\bootstrap\Html::a('返回列表',['brand/index'],['class'=>'btn btn-info'])?>
<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>LOGO</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=\yii\bootstrap\Html::img($model->logoUrl(),['style'=>'max-height:40px'])?></td>
            <td><?=$model->sort?></td>
            <td><?=\backend\models\Brand::$status_list[$model->status]?></td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['brand/edit','id'=>$model->id],['class'=>'btn btn-info'])?>
                <?=\yii\bootstrap\Html::a('恢复',['brand/restore','id'=>$model->id],['class'=>'btn btn-warning'])?>
                <?=\yii\bootstrap\Html::a('删除',['brand/delete','id'=>$model->id],['class'=>'btn btn-danger'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $pager,
])?>
