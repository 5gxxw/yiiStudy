<div class="row" style="margin-top: 70px">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
<?php
/**
 * 注册管理员
 */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password')->passwordInput();
echo $form->field($model,'repassword')->passwordInput();
echo $form->field($model,'email');
//>>用户关联角色
echo $form->field($model,'roles')->dropDownList(\backend\models\Admin::getRolesOption(),$options = ['multiple'=>'multiple']);
echo $form->field($model,'captcha')->widget(\yii\captcha\Captcha::className(),[
    //验证码
    'template' => '<div class="row"><div class="col-lg-6">{input}</div> <div class="col-lg-4">{image}</div></div>'
]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

?>
    </div>
</div>