
<div class="login w990 bc mt10 regist">
    <div class="login_hd">
        <h2>用户注册</h2>
        <b></b>
    </div>
    <div class="login_bd">
        <div class="login_form fl">
<?php
$form = \yii\widgets\ActiveForm::begin();
echo '<ul>';
echo $form->field($model,'username',
    [
        'options' => ['tag' => 'li'],   //将div改为li标签
        'errorOptions' => ['tag' => 'p'],   //将错误提示的div标签改为p标签
        'template' => "{label}\n{input}\n{hint}\n{error}",
    ]
)->textInput(['class'=>'txt','placeholder'=>'3-20位字符，可由中文、字母、数字和下划线组成']);

echo $form->field($model,'password',[
    'options' => ['tag' => 'li'],
    'errorOptions' => ['tag' => 'p'],
    'template' => "{label}\n{input}\n{hint}\n{error}",
])->passwordInput(['class'=>'txt','placeholder'=>'6-20位字符，可使用字母、数字和符号的组合，不建议使用纯数字、纯字母、纯符号']);

echo $form->field($model,'repassword',[
    'options' => ['tag' => 'li'],
    'errorOptions' => ['tag' => 'p'],
    'template' => "{label}\n{input}\n{hint}\n{error}",
])->passwordInput(['class'=>'txt','placeholder'=>'请再次输入密码']);

echo $form->field($model,'email',[
    'options' => ['tag' => 'li'],
    'errorOptions' => ['tag' => 'p'],
    'template' => "{label}\n{input}\n{hint}\n{error}",
])->textInput(['class'=>'txt','placeholder'=>'邮箱必须合法']);

echo $form->field($model,'tel',[
    'options' => ['tag' => 'li'],
    'errorOptions' => ['tag' => 'p'],
    'template' => "{label}\n{input}\n{hint}\n{error}",
])->textInput(['class'=>'txt']);
//>>定义成变量,加入template中
//$button = '<input type="button" onclick="bindPhoneNum(this)" id="get_captcha" value="获取验证码" style="height: 25px;padding:0px 8px; margin-left:4px;"/>';
//echo $form->field($model,'captcha_note',[
//    'options' => ['tag' => 'li'],
//    'errorOptions' => ['tag' => 'p'],
//    'template' => "{label}\n{input}{$button}\n{hint}\n{error}",
//])->textInput(['class'=>'txt','placeholder'=>'请输入短信验证码','disabled'=>"disabled",'id'=>'captcha']);

$span = '<span>看不清？<a href="">换一张</a></span>';
echo $form->field($model,'captcha',[
    'options' => ['tag' => 'li','class'=>'checkcode'],
    'errorOptions' => ['tag' => 'p'],
])->widget(yii\captcha\Captcha::className(),[
        'template' => "{input} {image}{$span}",
]);

echo $form->field($model,'checked',[
    'options' => ['tag' => 'li'],  //包裹输入框的li标签
    'errorOptions' => ['tag' => 'p'],
    'template' => "{label}\n{input}\n{hint}\n{error}",
])->checkbox().' 我已阅读并同意《用户注册协议》';

echo '<li><label for="">&nbsp;</label>'.\yii\helpers\Html::submitButton('',['class'=>'login_btn']).'</li>';
echo '</ul>';
\yii\widgets\ActiveForm::end();
?>
        </div>

        <div class="mobile fl">
            <h3>手机快速注册</h3>
            <p>中国大陆手机用户，编辑短信 “<strong>XX</strong>”发送到：</p>
            <p><strong>1069099988</strong></p>
        </div>

    </div>
</div>