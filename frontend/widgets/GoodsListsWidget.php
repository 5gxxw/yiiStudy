<?php
/**
 * 商品列表小部件
 */

namespace frontend\widgets;


use yii\base\Widget;
use yii\helpers\Html;

class GoodsListsWidget extends Widget
{
    public $goods;
    public function run(){
        $html = '';
        foreach($this->goods as $good){
           $html .= ' <li>
                <dl>
                    <dt>'.Html::a(Html::img('http://admin.yiishop.com/'.$good->logo),['goods/index','id'=>$good->id]).'</dt>
                    <dd>'.Html::a($good->name,['goods/index','id'=>$good->id]).'</dt>
                    <dd><strong>￥'.$good->shop_price.'</strong></dt>
                    <dd><a href=""><em>已有10人评价</em></a></dt>
                </dl>
            </li>';
        }

        return $html;
    }
}