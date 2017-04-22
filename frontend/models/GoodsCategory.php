<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "goods_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $tree
 * @property integer $lft
 * @property integer $rgt
 * @property integer $depth
 * @property integer $parent_id
 * @property string $intro
 */
class GoodsCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'tree', 'lft', 'rgt', 'depth', 'parent_id'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
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
            'tree' => '树',
            'lft' => '左',
            'rgt' => '右',
            'depth' => '深度',
            'parent_id' => 'Parent ID',
            'intro' => 'Intro',
        ];
    }

    /**
     * 给商品分类建立关系
     */
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
