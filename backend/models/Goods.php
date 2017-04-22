<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "goods".
 *
 * @property integer $id
 * @property string $name
 * @property string $sn
 * @property string $logo
 * @property integer $goods_category_id
 * @property integer $brand_id
 * @property string $market_price
 * @property string $shop_price
 * @property integer $stock
 * @property integer $is_on_sale
 * @property integer $status
 * @property integer $sort
 * @property integer $input_time
 */
class Goods extends \yii\db\ActiveRecord
{

    public $logo_file;  //保存上传文件对象
    public static $is_on_sale_list = [1=>'是',0=>'否'];
    public static $status_list = [1=>'正常',0=>'回收站'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','goods_category_id','brand_id','market_price','shop_price','stock','is_on_sale', 'status','sort'], 'required'],
            [['goods_category_id', 'brand_id', 'stock', 'is_on_sale', 'status', 'sort'], 'integer'],
            [['market_price', 'shop_price'], 'number'],
            [['name'], 'string', 'max' => 50],
            [['sn'], 'string', 'max' => 15],
            [['logo'], 'string', 'max' => 150],
            [['logo_file'],'safe'],
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
            'sn' => '货号',
            'logo' => '商品logo',
            'goods_category_id' => '商品分类',
            'brand_id' => '品牌',
            'market_price' => '市场价格',
            'shop_price' => '本店价格',
            'stock' => '库存',
            'is_on_sale' => '是否上架',
            'status' => '状态',
            'sort' => '排序',
        ];
    }

    /**
     * 获取商品分类
     */
    public static function getGoodsCategoryAll(){
        $goodsCategory =  GoodsCategory::find()->all();
        return ArrayHelper::toArray($goodsCategory);
    }

    /**
     * 品牌
     */
    public static function getBrandAll(){
        $brand = Brand::find()->all();
        return ArrayHelper::map($brand,'id','name');
    }

    /**
     * 关联商品分类:一对一
     */
    public function getGoodsCategory(){
        return $this->hasOne(GoodsCategory::className(),['id'=>'goods_category_id']);
    }

    /**
     * 关联品牌:一对一
     */
    public function getBrand(){
        return $this->hasOne(Brand::className(),['id'=>'brand_id']);
    }


    public function logoUrl(){
        if(strpos($this->logo,'http://') === false){
            $this->logo = '@web/'.$this->logo;
        }
        return $this->logo;
    }

    /**
     * @return bool
     * 判断商品分类的深度是否是最后一层
     */
    public function isCategory(){
        //>>根据用户传入的id获取到商品分类数据
        $model_category = GoodsCategory::findOne(['id'=>$this->goods_category_id]);
        //>>获取所有商品分类数据
        $categories = GoodsCategory::find()->all();
        //>>循环
        foreach ($categories as $category){
            if($model_category->tree == $category->tree && $model_category->lft < $category->lft && $model_category->rgt > $category->rgt){
                return false;
            }
        }
        return true;
    }
}
