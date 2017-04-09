<?php
/**
 *  使用静态方法调用状态,下标为保存到数据库的状态值.
 */
?>
<h1>品牌列表</h1>
<?=\yii\bootstrap\Html::a('添加品牌',['brand/add'],['class'=>'btn btn-info'])?>
<?=\yii\bootstrap\Html::a('进入回收站',['brand/retrieve'],['class'=>'btn btn-info'])?>
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
            <?=\yii\bootstrap\Html::a('查看',['brand/intro','id'=>$model->id],['class'=>'btn btn-success intro'])?>
            <?=\yii\bootstrap\Html::a('编辑',['brand/edit','id'=>$model->id],['class'=>'btn btn-info'])?>
            <?=\yii\bootstrap\Html::a('回收站',['brand/del','id'=>$model->id],['class'=>'btn btn-danger'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $pager,
    'nextPageLabel' => '下一页',
    'prevPageLabel' => '上一页',
])?>

