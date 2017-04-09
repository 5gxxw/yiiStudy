<h1>角色列表</h1>
<?=\yii\bootstrap\Html::a('添加角色',['rbac/roles-add'],['class'=>'btn btn-info'])?>

<table class="table table-hover table-bordered">
    <tr>
        <th>角色名称</th>
        <th>描述</th>
        <th>
            操作
        </th>
    </tr>
    <?php foreach($roles as $role):?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['rbac/roles-edit','name'=>$role->name],['class'=>'btn btn-info'])?>
                <?=\yii\bootstrap\Html::a('删除',['rbac/roles-del','name'=>$role->name],['class'=>'btn btn-danger','data'=>['confirm'=>'确认删除?']])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
