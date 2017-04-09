<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/3
 * Time: 10:30
 */

namespace backend\models;


use yii\base\Model;
use yii\db\ActiveQuery;

class GoodsSearchForm extends Model
{
    /**
     * 定义属性
     */
    public $name;
    public $sn;
    public $minPrice;
    public $maxPrice;

    /**
     * 定义验证规则
     */
    public function rules(){
        return [
            ['name','string','max'=>50],
            ['sn','string','max'=>16],
            [['minPrice','maxPrice'],'double'],
        ];
    }

    /**
     * 指定字段名称
     */
    public function attributeLabels(){
        return [
            'name' => '商品名称',
            'sn' => '货号',
            'minPrice' => '最小价格',
            'maxPrice' => '最大价格',
        ];
    }

    public function search(ActiveQuery $query){
        //>>接收get请求传输的数据
        $this->load(\Yii::$app->request->get());
        if($this->name){
            $query->andWhere(['like','name',$this->name]);
        }
        if($this->sn){
            $query->andWhere(['like','sn',$this->sn]);
        }
        if($this->minPrice){
            $query->andWhere(['>=','shop_price',$this->minPrice]);
        }
        if($this->maxPrice){
            $query->andWhere(['<=','shop_price',$this->maxPrice]);
        }
    }
}