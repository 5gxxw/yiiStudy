<?php

/**
 * 商品分类小部件
 */

namespace frontend\widgets;

use yii\helpers\Html;
use frontend\models\GoodsCategory;

class GoodsCategoryWidget extends \yii\base\Widget
{

    public $expand = false;     //>>是否展开商品分类

    public function run(){

        //>>使用缓存
//        $cache = \Yii::$app->cache;
//        //>>先获取缓存
//        $id = 'goods_category'.$this->expand;
//        $html = $cache->get($id);
//        //>>如果有就返回
//        if($html){
//            return $html;
//        }

        $cat1 = $this->expand ?'':'cat1';
        $none = $this->expand ?'':'none';

        //>>获取一级分类
        $categoryOne = GoodsCategory::find()->where(['parent_id'=>0])->all();

        //>>遍历一级分类
        $html = '';
        foreach($categoryOne as $k=>$category){
            //>>一级分类名称
            $html .= '<div class="cat '.($k==0?'item1':'').'">
                        <h3>'.Html::a($category->name,['index/list','id'=>$category->id]).'<b></b></h3>
                      <div class="cat_detail">';
            //>>查询出二级分类,遍历
            foreach($category->children as $childtwo){
                $two = Html::a($childtwo->name,['index/list','id'=>$childtwo->id]);
                $html .= "<dl class='dl_1st'>
                             <dt>
                                {$two}
                             </dt>
                          <dd>";
                //>>查询出三级分类,遍历
                foreach($childtwo->children as $childthree)
                    $html .= Html::a($childthree->name,['index/list','id'=>$childtwo->id]);

                $html .= "</dd>
                        </dl>";
            }
            $html .= '</div>
                </div>';
        }

        $html = <<<EOT
            <div class="category fl {$cat1}"> <!-- 非首页，需要添加cat1类 -->
				<div class="cat_hd">  <!-- 注意，首页在此div上只需要添加cat_hd类，非首页，默认收缩分类时添加上off类，鼠标滑过时展开菜单则将off类换成on类 -->
					<h2>全部商品分类</h2>
					<em></em>
				</div>
				<div class="cat_bd {$none}">
				    {$html}
				</div>
            </div>
EOT;

        //>>返回数据之前先保存到缓存
//        $cache->set($id,$html,3600*24*7);
//        var_dump($cat1);exit;
        return $html;
    }

}