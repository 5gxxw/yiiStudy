<?php

namespace backend\models;

use Yii;
use yii\test\InitDbFixture;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @var
     *  $logo_file 保存logo文件上传对象
     *  $status_list 静态属性保存状态,方便调用
     */
//    public $logo_file;
    public static $status_list = ['-1'=>'回收站',0=>'隐藏',1=>'显示'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','logo'], 'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 100],
//            [['logo'],'file','extensions'=>['jpg','png','gif'],'skipOnEmpty'=>true],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '品牌名称',
            'intro' => '简介',
            'logo' => 'LOGO',
            'sort' => '排序',
            'status' => '状态',
//            'logo_file' => 'LOGO',
        ];
    }

    public function logoUrl(){
        if(strpos($this->logo,'http://') === false){
            $this->logo = '@web'.$this->logo;
        }
        return $this->logo;
    }
}
