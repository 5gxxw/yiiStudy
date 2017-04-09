

<h1>商品分类列表</h1>
<?php
    echo \yii\bootstrap\Html::a('添加商品分类',['goods-category/add'],['class'=>'btn btn-info'])
?>
<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>操作</th>
    </tr>
    <tbody class="content">
    <?php foreach($models as $model):?>
        <tr data-lft="<?=$model->lft?>" data-rgt="<?=$model->rgt?>" data-tree="<?=$model->tree?>">
            <td><?=$model->id?></td>
            <td style="width: 500px;"><?=str_repeat('－－',$model->depth).$model->name?><span class="glyphicon glyphicon-chevron-down press" style="float:right"></span></td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['goods-category/edit','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods-category/del','id'=>$model->id],['class'=>'btn btn-info btn-xs'])?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<?php
$js = <<<EOT
//鼠标按下箭头
$(".press").click(function(){
    $(this).toggleClass('glyphicon glyphicon-chevron-down');
    $(this).toggleClass('glyphicon glyphicon-chevron-up');
    
    var tr = $(this).closest('tr');//获取点击的tr
    var lft = tr.attr('data-lft');//获取点击的tr的左值
    var rgt = tr.attr('data-rgt');//获取点击的tr的右值
    var tree = tr.attr('data-tree');//获取点击的tr的树
   
    $(".content tr").each(function(){  //遍历tbody下的tr
        var p_lft = $(this).attr('data-lft');   //获取tr的左值
        var p_rgt = $(this).attr('data-rgt');   //获取tr的右值
        var p_tree = $(this).attr('data-tree'); //获取tr的树
        if(lft < p_lft && rgt > p_rgt && tree == p_tree){
            $(this).fadeToggle();
        }
    });
});
EOT;

$this->registerJs($js);