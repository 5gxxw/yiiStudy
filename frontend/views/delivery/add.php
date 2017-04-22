<?php
/**
 * 添加收货地址
 * @var $this \yii\web\View
 */
?>
<div class="content fl ml10">
    <div class="address_bd mt10">
        <h4>新增收货地址</h4>
<?php
    $form = \yii\widgets\ActiveForm::begin();
    echo '<ul>';
    echo $form->field($model,'name',[
        'options' => ['tag' => 'li'],   //将div改为li标签
        'errorOptions' => ['tag' => 'p','style'=>'margin-left: 60px;margin-top: 5px;'],   //将错误提示的div标签改为p标签
        'template' => "{label}\n{input}\n{hint}\n{error}",
    ])->textInput(['class'=>'txt']);
    echo '<li>';
    echo $form->field($model,'province',[
        'options' => ['tag' => false],   //将div改为li标签
        'template' => "{label}\n{input}\n{hint}",
    ])->dropDownList([],['id'=>'cmbProvince']);
    echo $form->field($model,'city',[
        'options' => ['tag' => false,],   //将div改为li标签
        'template' => "{input}\n{hint}",
    ])->dropDownList([],['id'=>'cmbCity']);

    echo $form->field($model,'area',[
        'options' => ['tag' => false],   //将div改为li标签
        'template' => "{input}\n{hint}",
    ])->dropDownList([],['id'=>'cmbArea']);
    echo '</li>';



    echo $form->field($model,'particular',[
        'options' => ['tag' => 'li'],   //将div改为li标签
        'labelOptions' =>[''],
        'errorOptions' => ['tag' => 'p','style'=>'margin-left: 60px;margin-top: 5px;'],   //将错误提示的div标签改为p标签
        'template' => "{label}\n{input}\n{hint}\n{error}",
    ])->textInput(['class'=>'txt']);
    echo $form->field($model,'tel',[
        'options' => ['tag' => 'li'],   //将div改为li标签
        'labelOptions' =>[''],
        'errorOptions' => ['tag' => 'p','style'=>'margin-left: 60px;margin-top: 5px;'],   //将错误提示的div标签改为p标签
        'template' => "{label}\n{input}\n{hint}\n{error}",
    ])->textInput(['class'=>'txt']);
    echo $form->field($model,'status',[
        'options' => ['tag' => 'li'],   //将div改为li标签
        'errorOptions' => ['tag' => 'p','style'=>'margin-left: 60px;margin-top: 5px;'],   //将错误提示的div标签改为p标签
        'template' => "{label}\n{input}",
    ])->checkbox();
    echo \yii\helpers\Html::submitButton('保存',['chass'=>'btn']);
    echo '</ul>';
    \yii\widgets\ActiveForm::end();
?>
    </div>

</div>
<?php


$js = <<<JS
   addressInit('cmbProvince', 'cmbCity', 'cmbArea', '请选择', '请选择', '请选择');
JS;
$this->registerJs($js);

$this->registerJsFile('@web/js/jsAddress.js');
?>