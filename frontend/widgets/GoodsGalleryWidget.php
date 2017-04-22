<?php
/**
 * 图片预览小部件
 */

namespace frontend\widgets;


use backend\models\GoodsGallery;
use yii\base\Widget;
use yii\helpers\Html;

class GoodsGalleryWidget extends Widget
{
    public $goods_id;

    public function run(){
        //>>根据id获取相册信息
        $models = GoodsGallery::find()->where(['goods_id'=>$this->goods_id])->all();
        $html = '';
//        $e = '<div class="preview fl">
//                <div class="midpic">
//                '.Html::a(Html::img(Html::img("http://admin.yiishop.com".$model->path)),[Html::img("http://admin.yiishop.com".$model->path)],['class'=>'jqzoom','rel'=>'gal1']).'
//
//                </div>
//                <!--使用说明：此处的预览图效果有三种类型的图片，大图，中图，和小图，取得图片之后，分配到模板的时候，把第一幅图片分配到 上面的midpic 中，其中大图分配到 a 标签的href属性，中图分配到 img 的src上。 下面的smallpic 则表示小图区域，格式固定，在 a 标签的 rel属性中，分别指定了中图（smallimage）和大图（largeimage），img标签则显示小图，按此格式循环生成即可，但在第一个li上，要加上cur类，同时在第一个li 的a标签中，添加类 zoomThumbActive  -->
//                <div class="smallpic">
//                    <a href="javascript:;" id="backward" class="off"></a>
//                    <a href="javascript:;" id="forward" class="on"></a>
//                    <div class="smallpic_wrap">
//                        <ul>';
        //>>遍历出相册
        if ($models){
            foreach($models as $k => $model){
                //$smallImg = Html::a(Html::img('http://admin.yiishop.com/'.$model->path),['','id'=>$model->id],['class'=>'zoomThumbActive']);
                $smallImg = '<a class="zoomThumbActive" href="javascript:void(0);" 
    rel="{gallery: \'gal1\', smallimage: '.Html::img('http://admin.yiishop.com/'.$model->path).''.Html::img('http://admin.yiishop.com/'.$model->path).'}">
    '.Html::img('http://admin.yiishop.com/'.$model->path).'</a>';
                if($k == 0){
                    $img = Html::a(Html::img('http://admin.yiishop.com/'.$model->path),['http://admin.yiishop.com/'.$model->path],['class'=>'jqzoom','rel'=>'gal1']);
                    $html .= <<<HTML
            <div class="preview fl">
                <div class="midpic">
                    $img
                </div>

                <!--使用说明：此处的预览图效果有三种类型的图片，大图，中图，和小图，取得图片之后，分配到模板的时候，把第一幅图片分配到 上面的midpic 中，其中大图分配到 a 标签的href属性，中图分配到 img 的src上。 下面的smallpic 则表示小图区域，格式固定，在 a 标签的 rel属性中，分别指定了中图（smallimage）和大图（largeimage），img标签则显示小图，按此格式循环生成即可，但在第一个li上，要加上cur类，同时在第一个li 的a标签中，添加类 zoomThumbActive  -->

                <div class="smallpic">
                    <a href="javascript:;" id="backward" class="off"></a>
                    <a href="javascript:;" id="forward" class="on"></a>
                    <div class="smallpic_wrap">
                        <ul>

                            <li class="cur">
                                {$smallImg}
                            </li>
HTML;
                }else{
                $html .= <<<HTML
                            <li class="cur">
                                {$smallImg}
                            </li>
HTML;

                }
            }
            $html .= <<<HTML
                        </ul>
                    </div>

                </div>
            </div>
HTML;
        }
        return $html;
    }
}