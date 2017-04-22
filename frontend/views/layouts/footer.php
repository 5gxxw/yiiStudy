<?php
/**
 * @var $this \yii\web\View
 */
use \yii\helpers\Html;
use frontend\models\Article;
use frontend\models\ArticleCategory;

//>>2.读取底部导航数据
//>>获取文章分类
$art_category = ArticleCategory::find()->all();
//>>获取文章
$articles = Article::find()->all();
?>
<div style="clear:both;"></div>
<!-- 底部导航 start -->
<div class="bottomnav w1210 bc mt10">

    <!-- 遍历文章分类 -->
    <?php foreach($art_category as $footer):?>
        <div class="bnav1">
            <!-- 显示文章分类的名称 -->
            <h3><b></b> <em><?=$footer->name?></em></h3>
            <ul>
                <!-- 遍历文章 -->
                <?php foreach($articles as $article):?>
                    <?php
                        if ($article->article_category_id == $footer->id){
                            echo '<li>'.Html::a($article->name).'</li>';
                        }
                    ?>
                <?php endforeach;?>
            </ul>
        </div>
    <?php endforeach;?>
</div>
<!-- 底部导航 end -->

<div style="clear:both;"></div>

<!-- 底部版权 start -->
<div class="footer w1210 bc mt10">
    <p class="links">
        <a href="">关于我们</a> |
        <a href="">联系我们</a> |
        <a href="">人才招聘</a> |
        <a href="">商家入驻</a> |
        <a href="">千寻网</a> |
        <a href="">奢侈品网</a> |
        <a href="">广告服务</a> |
        <a href="">移动终端</a> |
        <a href="">友情链接</a> |
        <a href="">销售联盟</a> |
        <a href="">京西论坛</a>
    </p>
    <p class="copyright">
        © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号
    </p>
    <p class="auth">
        <a href=""><?=\yii\helpers\Html::img('@web/images/xin.png')?></a>
        <a href=""><?=Html::img('@web/images/kexin.jpg')?></a>
        <a href=""><?=Html::img('@web/images/kexin.jpg')?></a>
        <a href=""><?=Html::img('@web/images/beian.gif')?></a>
    </p>
</div>
<!-- 底部版权 end -->
