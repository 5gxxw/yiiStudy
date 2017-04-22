<?php
/**
 * 购物车回显数据的小部件
 */

namespace frontend\widgets;


use backend\models\Goods;
use yii\base\Widget;
use yii\helpers\Html;

class CartWidget extends Widget
{
    public $models;  //>>用户购物车中的商品id和数量
    public function run(){
        $html = '<tbody>';
        //>>1.根据商品id查询出商品信息回显
        foreach ($this->models as $model){
            $good = Goods::findOne(['id'=>$model['id']]);

            $html .= '<tr data-goods-id="'.$good->id.'">
            <td class="col1">'.Html::a(Html::img('http://admin.yiishop.com/'.$good->logo),['goods/index',['id'=>$good->id]]).'  <strong>'.Html::a($good->name,['goods/index',['id'=>$good->id]]).'</strong></td>
            <td class="col3">￥<span>'.$good->shop_price.'</span></td>
            <td class="col4">
                <a href="javascript:;" class="reduce_num"></a>
                <input type="text" name="num" value="'.$model['num'].'" class="amount"/>
                <a href="javascript:;" class="add_num"></a>
            </td>
            <td class="col5">￥<span>'.$good->shop_price * $model['num'].'</span></td>
            <td class="col6"><a href="javascript:;" class="btn_del">删除</a></td>
        </tr>';
        }
        $html .= ' </tbody>
        <tfoot>
        <tr>
            <td colspan="6">购物金额总计： <strong>￥ <span id="total">4999.00</span></strong></td>
        </tr>
        </tfoot>';
        return $html;
    }
}