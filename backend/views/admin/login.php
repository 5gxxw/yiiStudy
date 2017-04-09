<div class="row" style="margin-top: 100px">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <?php
        /**
         * 登录表单
         */
        $form = \yii\bootstrap\ActiveForm::begin();
        echo $form->field($model,'username');
        echo $form->field($model,'password')->passwordInput();
        echo $form->field($model,'captcha')->widget(\yii\captcha\Captcha::className(),[
            'template' => '<div class="row"><div class="col-lg-8">{input}</div><div class="col-lg-4">{image}</div></div>'
        ]);
        echo $form->field($model,'rememberMe')->checkbox();
        ?>
        <div>
            <?php
            echo \yii\bootstrap\Html::submitButton('登录',['class'=>'btn btn-info']);
//            echo \yii\bootstrap\Html::a('注册',['admin/add'],['class' => 'btn btn-info']);
            ?>
        </div>
        <?php
        \yii\bootstrap\ActiveForm::end();
        ?>
    </div>
</div>