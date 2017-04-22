<?php
/**
 * @var $this \yii\web\View
 */
use backend\models\Goods;
use yii\base\Widget;
use yii\helpers\Html;

$this->title = '购物车页面';
$this->registerCssFile('@web/style/cart.css');
$this->registerJsFile('@web/js/cart1.js',['depends'=>'yii\web\JqueryAsset']);
?>

<div style="clear:both;"></div>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><img src="<?=Yii::getAlias('@web/')?>images/logo.png" alt="京西商城"></a></h2>
        <div class="flow fr">
            <ul>
                <li class="cur">1.我的购物车</li>
                <li>2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="mycart w990 mt10 bc">
    <h2><span>我的购物车</span></h2>
    <table>
        <thead>
        <tr>
            <th class="col1">商品名称</th>
            <th class="col3">单价</th>
            <th class="col4">数量</th>
            <th class="col5">小计</th>
            <th class="col6">操作</th>
        </tr>
        </thead>

        <?=\frontend\widgets\CartWidget::widget(['models'=>$models])?>

    </table>
    <div class="cart_btn w990 bc mt10">
        <a href="" class="continue">继续购物</a>
        <?=\yii\helpers\Html::a('结算',['order/order',],['class'=>'checkout'])?>
    </div>
</div>
<!-- 主体部分 end -->

<?php
$this->registerJs('totalPrice()');
