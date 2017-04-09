<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/9
 * Time: 11:24
 */

namespace frontend\assets;


use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'style/base.css',
        'style/global.css',
        'style/header.css',
        'style/login.css',
        'style/footer.css',
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\JqueryAsset',  //加载jquery文件
        //'yii\web\YiiAsset',
        //'yii\bootstrap\BootstrapAsset',
    ];
}