<div class="content fl ml10">
    <div class="address_hd">
        <div class="address_bd mt10">
        <h3>收货地址薄</h3>
        <?php foreach($models as $model):?>
            <dl>
                <dt><?=$model->id;?>.<?=$model->name;?> <?=$model->province;?> <?=$model->city;?> <?=$model->area;?> <?=$model->particular;?> <?=$model->tel;?> </dt>
                <dd>
                    <?php
                    echo \yii\helpers\Html::a('修改',['delivery/edit','id'=>$model->id]).'　';
                    echo \yii\helpers\Html::a('删除',['delivery/del','id'=>$model->id]).'　';
                    if ($model->status == 1){
                        echo \yii\helpers\Html::a('取消默认地址',['delivery/default','id'=>$model->id]);
                    }else{
                        echo \yii\helpers\Html::a('设为默认地址',['delivery/default','id'=>$model->id]);
                    }
                    ?>
                </dd>
            </dl>
        <?php endforeach;?>
            <h3><?= \yii\helpers\Html::a('新增收货地址',['delivery/add']);?></h3>
        </div>
    </div>
</div>