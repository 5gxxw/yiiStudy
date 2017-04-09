<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_gallery".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $path
 */
class GoodsGallery extends \yii\db\ActiveRecord
{

    public $path_list;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['path'], 'required'],
            [['path'], 'string', 'max' => 255],
            [['path_list'], 'file', 'maxFiles' => 10,'extensions'=>['jpg','png','gif']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品ID',
            'path' => '商品图片地址',
            'path_list' => '添加商品相册',
        ];
    }

    /**
     * @return bool
     * 循环保存上传文件
     */



    public function logoUrl(){

        if(strpos($this->path,'http://') === false){
            $this->path = '@web/'.$this->path;
        }
        return $this->path;
    }
}
