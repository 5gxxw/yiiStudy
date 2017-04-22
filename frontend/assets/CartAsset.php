<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/11
 * Time: 12:36
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class CartAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/footer.css',
    ];
    public $js = [

    ];
    public $depends = [
        'yii\web\JqueryAsset',  //加载jquery文件
    ];
}