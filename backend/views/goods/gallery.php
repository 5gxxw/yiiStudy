<h1>商品相册</h1>
<?php
use yii\bootstrap\Html;

echo Html::a('返回列表',['goods/index'],['class'=>'btn btn-info','style'=>'margin:30px;']);
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($gallery_add, 'path_list[]')->fileInput(['multiple' => true]);
echo Html::submitButton('提交', ['class'=>'btn btn-primary','name' =>'submit-button']);
\yii\bootstrap\ActiveForm::end();
?>

<div>
    <table class="table">
        <?php foreach($models as $model):?>
        <tr>
            <td><?=\yii\bootstrap\Html::img($model->logoUrl(),['style'=>'height:100px'])?></td>
            <td><?=\yii\bootstrap\Html::a('删除',['goods/del-gallery','id'=>$model->id,'present_id'=>$present_id],['class'=>'btn btn-danger'])?></td>
        </tr>
        <?php endforeach;?>
    </table>
</div>
