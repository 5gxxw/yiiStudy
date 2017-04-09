
<h1>文章分类列表</h1>

<?=\yii\bootstrap\Html::a('添加分类',['article-category/add'],['class'=>'btn btn-info'])?>
<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>分类名称</th>
        <th>排序</th>
        <th>状态</th>
        <th>是否是帮助相关分类</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->sort?></td>
            <td><?=$model->status_option[$model->status]?></td>
            <td><?=$model->is_help_option[$model->is_help]?></td>
            <td>
                <?=\yii\bootstrap\Html::a('查看',['article-category/article-list','id'=>$model->id],['class'=>'btn btn-success intro'])?>
                <?=\yii\bootstrap\Html::a('编辑',['article-category/edit','id'=>$model->id],['class'=>'btn btn-info'])?>
                <?=\yii\bootstrap\Html::a('删除',['article-category/del','id'=>$model->id],['class'=>'btn btn-danger'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>


