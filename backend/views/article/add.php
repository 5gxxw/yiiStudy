<?php
/**
 * 添加文章视图
 */

$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'article_category_id')->dropDownList(\backend\models\Article::getArticleCategoryAll());
echo $form->field($model,'intro')->textarea();
echo $form->field($model,'sort');
echo $form->field($model,'status',['inline'=>true])->radioList($model->status_option);
echo $form->field($detail,'content')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
