
<h1>管理员列表</h1>

<?=\yii\bootstrap\Html::a('添加管理员',['admin/add'],['class' => 'btn btn-info','style' => 'margin-bottom:10px'])?>
<table class="table table-hover table-bordered">
    <tr>
        <th>id</th>
        <th>账号</th>
        <th>邮箱</th>
        <th>注册时间</th>
        <th>最后登录时间</th>
        <th>最后登录ip</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->username?></td>
            <td><?=$model->email?></td>
            <td><?=date('Y-m-d H:i:s',$model->add_time)?></td>
            <td><?=date('Y-m-d H:i:s',$model->last_login_time)?></td>
            <td><?=$model->last_login_ip?></td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['admin/edit','id'=>$model->id],['class'=>'btn btn-success btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['admin/del','id'=>$model->id],['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>

</table>
