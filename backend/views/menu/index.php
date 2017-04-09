<?php
/* @var $this yii\web\View */
?>
<h1>菜单列表</h1>
<?=\yii\bootstrap\Html::a('添加菜单',['menu/add'],['class'=>'btn btn-info btn-sm'])?>
<table class="table table-bordered table-hover">
    <tr>
        <th>id</th>
        <th style="width:50%">名称</th>
        <th>权限(路由)</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->url?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['menu/edit','id'=>$model->id],['class'=>'btn btn-success btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$model->id],['class'=>'btn btn-success btn-sm','data'=>['confirm'=>'确认删除?']])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
