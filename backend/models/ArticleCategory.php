<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $status
 * @property integer $sort
 * @property integer $is_help
 */
class ArticleCategory extends \yii\db\ActiveRecord
{

    public $is_help_option = [1=>'是',0=>'否'];
    public $status_option = [1=>'是',0=>'否'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','is_help','status'], 'required'],
            [['intro'], 'string'],
            [['status', 'sort', 'is_help'], 'integer'],
            [['name'], 'string', 'max' => 50],
            ['name','unique']
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
            'intro' => '简介',
            'status' => '是否显示',
            'sort' => '排序',
            'is_help' => '是否是帮助相关的分类',
        ];
    }
}
