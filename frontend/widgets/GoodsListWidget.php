<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/11
 * Time: 23:45
 */

namespace frontend\widgets;


use yii\base\Widget;
use yii\helpers\Html;

class GoodsListWidget extends Widget
{
    public $models;
    public function run(){

//        var_dump($this->models);
        $html = '';
        //>>遍历$models
        foreach($this->models as $model){
            $html .= '<div class="child">
                    <h3 class="on"><b></b>'.$model->name.'</h3>
                    <ul>';
            foreach($model->children as $child){
                $html .= '<li>'.Html::a($child->name,['index/goods-list','id'=>$child->id]).'</li>';
            }
            $html .= '</ul>
                </div>';
        }
        $html = '<div class="catlist_wrap">'.$html. '<div style="clear:both; height:1px;"></div>
        </div>';

        return $html;
    }
}