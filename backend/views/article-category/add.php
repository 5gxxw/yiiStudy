<h1>文章分类</h1>
<?php
/**
 * 文章分类管理
 */

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'sort');
echo $form->field($model,'status',['inline'=>true])->radioList([1=>'是',0=>'否']);
echo $form->field($model,'is_help',['inline'=>true])->radioList([1=>'是',0=>'否']);
echo $form->field($model,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();