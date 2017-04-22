<?php
/**
 * 提交订单视图
 * @var $this \yii\web\View
 */
$this->title = '填写核对订单信息';
$this->registerCssFile('@web/style/fillin.css');
$this->registerJsFile('@web/js/cart2.js',['depends'=>'yii\web\JqueryAsset']);
?>
<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="<?=Yii::getAlias('@web')?>/images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->
<!-- 主体部分 start -->
<form action="/order/save" method="post">
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_select">
                <ul id="data-consignee">
                    <?php foreach($sites as $site):?>
                        <li class="">
                            <input type="radio" name="address" value="<?=$site->id?>" /><?=$site->name?> <?=$site->province?> <?=$site->city?>  <?=$site->area?> <?=$site->particular?> <?=$site->tel?>
                        </li>
                    <?php endforeach;?>
                </ul>
            </div>
        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody id="deliver_goods">
                    <?php foreach(\frontend\models\Order::$express as $k=>$expres):?>
                    <tr>
                        <td><input type="radio" name="delivery" value="<?=$k?>"/><?=$expres[0]?></td>
                        <td><?=$expres[1]?></td>
                        <td><?=$expres[2]?></td>
                    </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式</h3>
            <div class="pay_select">
                <table id="payment">
                    <?php foreach(\frontend\models\Order::$pay as $k=>$pay_type):?>
                    <tr class="">
                        <td class="col1"><input type="radio" name="pay" value="<?=$k?>"/><?=$pay_type[0]?></td>
                        <td class="col2"></td>
                    </tr>
                    <?php endforeach;?>
                </table>
            </div>
        </div>
        <!-- 支付方式  end-->


        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($models as $model):?>
                <tr>
                    <td class="col1"><?=\yii\helpers\Html::a(\yii\helpers\Html::img(Yii::$app->params['imgPath'].$model['logo']),['goods/index','id'=>$model['goods_id']])?>  <strong><?=\yii\helpers\Html::a($model['name'],['goods/index','id'=>$model['goods_id']])?></strong></td>
                    <td class="col3">￥<?=$model['price']?></td>
                    <td class="col4"> <?=$model['num']?></td>
                    <td class="col5"><span>￥<?=$model['subtotal']?></span></td>
                </tr>
                <?php endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>
                                <span><?=count($models)?> 件商品，总商品金额：</span>
                                <em id="total_prices"><?=$total_price?></em>
                            </li>
                            <li>
                                <span>返现：</span>
                                <em>￥0.00</em>
                            </li>
                            <li>
                                <span>运费：</span>
                                <em id="delivery_price">10.00</em>
                            </li>
                            <li>
                                <span>应付总额：</span>
                                <em id="price">￥<?=$total_price?></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <?=\yii\helpers\Html::submitButton('提交订单')?>
        <p>应付总额：<strong>￥5076.00元</strong></p>

    </div>
</div>
<!-- 主体部分 end -->
</form>