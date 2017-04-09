<?php
/**
 * 品牌简介
 */
?>
<div class="text-center">
    <p><?=$model->intro?></p>
</div>
<?=\yii\bootstrap\Html::a('返回',['brand/index'],['class'=>'btn btn-info'])?>