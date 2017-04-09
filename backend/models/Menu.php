<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "menu".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $url
 * @property string $intro
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
            [['name'], 'required'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 10],
            [['url'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => '上级分类',
            'name' => '菜单名称',
            'url' => '路由(权限)',
            'intro' => '描述信息',
        ];
    }

    /**
     * 获取上级分类
     */
    public static function parent(){
        $stair = [['id'=>'0','name'=>'顶级分类']];
        //获取所有一级分类
        $menu = Menu::find()->where(['parent_id'=>0])->all();
        $menu = ArrayHelper::merge($stair,$menu);
        //添加顶级分类
        $menu = ArrayHelper::map($menu,'id','name');
        //返回
        return $menu;
    }

    /*
     * 一级菜单和二级菜单 一对多关系
     */
    public function getMenus()
    {
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }
}
