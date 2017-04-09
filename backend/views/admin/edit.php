<div class="row">
    <div class="col-sm-3"></div>
    <div class="col-sm-4">
    <?php
        $form = \yii\bootstrap\ActiveForm::begin();
        echo $form->field($model,'username');
        echo $form->field($model,'password');
        \yii\bootstrap\ActiveForm::end();
    ?>
    </div>
</div>
