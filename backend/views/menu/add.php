<?php

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'url');
echo $form->field($model,'intro')->textarea();
//>>上级分类
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::parent());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();