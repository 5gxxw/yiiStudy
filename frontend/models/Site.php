<?php

namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "site".
 *
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $particular
 * @property string $tel
 * @property integer $status
 */
class Site extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'site';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'user_id', 'province', 'city', 'area', 'particular', 'tel'], 'required'],
            [['user_id', 'status'], 'integer'],
            [['name'], 'string', 'max' => 20],
            [['province', 'city', 'area', 'particular'], 'string', 'max' => 50],
            [['tel'], 'string', 'max' => 11],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '* 收 货 人 ：',
            'user_id' => '用户id',
            'province' => '* 所 在 地 ：',
//            'city' => '市',
//            'area' => '区',
            'particular' => '* 详细地址 :',
            'tel' => '* 手机号码 :',
            'status' => '是否为默认地址',
        ];
    }

    /**
     * 保存收货地址
     */
    public function DeliverySave(){
        //>>判断是否是默认地址,如果为1,则获取数据库状态为1的数据并改为0
        if($this->status == 1){
            $delivery = self::findOne(['status'=>1]);
            if ($delivery){
                $delivery->status = 0;
                $delivery->save();
            }
        }
        //>>保存
        $this->save();
    }

    /**
     * 修改默认地址
     */
    public function defaultDelivery(){
        if($this->status == 1){
            $this->status = 0;
        }else{
            $delivery = self::findOne(['status'=>1]);
            if ($delivery){
                $delivery->status = 0;
                $delivery->save();
            }
            $this->status = 1;
        }
        //>>保存
        return $this->save();
    }







    /**
     * 获取省
     */
    public static function getProvince(){
        //>>1.查询出所有的省
        $province = China::find()->where(['Pid'=>0])->asArray()->all();
        //>>添加请选择
        //>>2.格式化数据
//        $provinces = ArrayHelper::merge([0=>['Id'=>'0','Name'=>'请选择','Pid'=>0]],$province);
        return ArrayHelper::map($province,'Id','Name');
    }

    /**
     * 获取市
     */
    public function getCity($province){

    }

    /**
     * 获取区/县
     */
    public function getArea($city){

    }
}
