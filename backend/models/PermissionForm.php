<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/5
 * Time: 12:45
 */

namespace backend\models;


use yii\base\Model;

class PermissionForm extends Model
{
    public $name;   //权限名称

    public $description;    //描述

    public function rules(){
        return [
            [['name','description'],'required'],
            [['name'],'string','max'=>64],
            [['description'],'string','max' => 64],
            [['name'],'validateName'],
        ];
    }

    public function attributeLabels(){
        return [
            'name' => '权限名称(路由)',
            'description' => '描述信息',
        ];
    }

    /**
     * 名称不能重复验证规则
     */
    public function validateName($attribute,$params){
        //>>实例化rbac组件
        $authManager = \Yii::$app->authManager;
        //>>判断是否有该权限
        if($authManager->getPermission($this->$attribute)){
            //>>有权限,则
            return self::addError('name','该权限已经存在');
        }
    }

    /**
     * 保存权限
     */
    public function add(){
        //>>实例化
        $authManager = \Yii::$app->authManager;
        //>>3.1创建权限
        $permission = $authManager->createPermission($this->name);
        //>>保存描述
        $permission->description = $this->description;
        //>>3.2保存权限
        $authManager->add($permission);
    }
}