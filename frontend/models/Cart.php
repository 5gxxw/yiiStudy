<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property integer $num
 * @property integer $member_id
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id', 'num', 'member_id'], 'required'],
            [['goods_id', 'num', 'member_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品id',
            'num' => '数量',
            'member_id' => '用户id',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     * 和商品表建立多对多关系
     */
    public function getGoods(){
        return $this->hasMany(Goods::className(),['id'=>'goods_id']);
    }

}
