<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'content')->widget('kucha\ueditor\UEditor',[]);

\yii\bootstrap\ActiveForm::end();