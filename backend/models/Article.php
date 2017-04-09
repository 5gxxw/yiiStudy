<?php

namespace app\models;

use backend\models\ArticleCategory;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property integer $article_category_id
 * @property string $intro
 * @property integer $status
 * @property integer $sort
 * @property integer $input_time
 */
class Article extends \yii\db\ActiveRecord
{

    public $status_option = [1=>'是',0=>'否'];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['article_category_id', 'status', 'sort', 'input_time'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
            ['name','unique'],//唯一性
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'article_category_id' => '文章分类',
            'intro' => '简介',
            'status' => '状态',
            'sort' => '排序',
            'input_time' => '录入时间',
        ];
    }

    /**
     * 关联文章内容表
     * 一对一
     */
    public function getArticleDetail(){
        return $this->hasOne(ArticleDetail::className(),['article_id'=>'id']);
    }

    /**
     * 关联文章分类
     * 一对一
     */
    public function getArticleCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }

    /**
     * 查询文章分类列表
     */
    public static function getArticleCategoryAll(){
        $article_category = ArticleCategory::find()->all();
        return ArrayHelper::map($article_category,'id','name');
    }
}
