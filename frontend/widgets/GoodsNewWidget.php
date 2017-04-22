<?php
/**
 * 最新上架小部件
 */

namespace frontend\widgets;


use frontend\models\Goods;
use yii\base\Widget;
use yii\helpers\Html;

class GoodsNewWidget extends Widget
{
    public function run(){
        //1.获取最新上架的5个数据
        $news = \backend\models\Goods::find()->orderBy(['input_time'=>SORT_DESC])->limit(5)->all();
        $html = '';
        foreach ($news as $new){
            $html .='<li>
                        <dl>
                            <dt>'.Html::a(Html::img('http://admin.yiishop.com/'.$new->logo),['goods/index','id'=>$new->id]).'</dt>
                            <dd>'.Html::a(mb_substr($new->name,0,15).'...',['goods/index','id'=>$new->id]).'</dd>
                            <dd><span>售价：</span><strong> ￥'.$new->shop_price.'</strong></dd>
                        </dl>
                    </li>';
        }
        return $html;
    }
}