<h1>角色管理</h1>
<?php
/**
 * 角色添加
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description');
//>>给角色添加权限
echo $form->field($model,'permissions')->checkboxList(\backend\models\RolesForm::getPermissionOptions());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();