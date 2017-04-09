<h1>权限列表</h1>
<?=\yii\bootstrap\Html::a('添加权限',['permission-add'],['class'=>'btn btn-info'])?>
<table class="table table-hover table-bordered">
    <tr>
        <th>权限名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach($permissions as $permission):?>
    <tr>
        <td><?=$permission->name?></td>
        <td><?=$permission->description?></td>
        <td>
            <?=\yii\bootstrap\Html::a('删除',['rbac/permission-del','name'=>$permission->name],['class'=>'btn btn-danger','data'=>['confirm'=>'确认删除?']])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>

