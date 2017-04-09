<?php
/* @var $this yii\web\View */
?>
<h1>文章管理列表</h1>
<?=\yii\bootstrap\Html::a('添加文章',['article/add'],['class'=>'btn btn-info'])?>
<table class="table table-hover">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>文章分类</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>录入时间</th>
        <th>操作</th>
    </tr>

    <?php foreach($models as $model):?>
        <tr>
        <td><?=$model->id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->articleCategory->name?></td>
        <td><?=mb_substr($model->intro,0,20)?></td>
        <td><?=$model->sort?></td>
        <td><?=$model->status_option[$model->status]?></td>
        <td><?=date('Y-m-d H:i:s',$model->input_time)?></td>
        <td>
            <?=\yii\bootstrap\Html::a('查看',['article/content','id'=>$model->id],['class'=>'btn btn-success'])?>
            <?=\yii\bootstrap\Html::a('编辑',['article/edit','id'=>$model->id],['class'=>'btn btn-info'])?>
            <?=\yii\bootstrap\Html::a('删除',['article/del','id'=>$model->id],['class'=>'btn btn-danger'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>

<?=\yii\widgets\LinkPager::widget([
    'pagination' => $pager,
    'nextPageLabel' => '下一页',
    'prevPageLabel' => '上一页',
])?>
