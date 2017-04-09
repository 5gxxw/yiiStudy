<?php
/**
 * 角色表单模型
 */

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

class RolesForm extends Model
{
    public $name;   //角色名称

    public $description;    //角色描述

    public $permissions = [];   //保存所有权限

    const SCENARIO_ADD = 'add'; //定义一个场景

    //>>覆盖父类的场景
    public function scenarios(){
        //>>获取父类的场景
        $scenarios = parent::scenarios();
        //>>合并
        return ArrayHelper::merge($scenarios,[
            self::SCENARIO_ADD => ['name','description','permissions'],//指定有哪些字段,默认所有
        ]);
    }

    public function rules(){
        return [
            [['name','description'],'required'],
            [['name'],'string','max'=>64],
            [['description'],'string','max' => 64],
            [['name'],'validateName','on'=>self::SCENARIO_ADD],//定义在添加场景下才验证
            [['permissions'],'safe'],
        ];
    }

    public function attributeLabels(){
        return [
            'name' => '角色名称',
            'description' => '描述信息',
            'permissions' => '选择权限',
        ];
    }

    /**
     * 名称不能重复验证规则
     */
    public function validateName($attribute,$params){
        //>>实例化rbac组件
        $authManager = \Yii::$app->authManager;
        //>>判断是否有该角色
        if($authManager->getRole($this->$attribute)){
            //>>
            return self::addError('name','角色已存在');
        }
    }

    /**
     * 获取所有权限选项
     */
    public static function getPermissionOptions()
    {
        //>>1.实例化rbac组件
        $authManager = \Yii::$app->authManager;
        //>>2.获取所有权限
        $permissions = $authManager->getPermissions();

        return ArrayHelper::map($permissions,'name','description');

    }

    /**
     * @return mixed
     * 保存角色
     */
    public function save($role = null)
    {
        $authManager = \Yii::$app->authManager;
        //>>添加角色
        if($this->scenario == self::SCENARIO_ADD){
            //>>3.1创建角色
            $roles = $authManager->createRole($this->name);
            //>>保存描述信息
            $roles->description = $this->description;
            //>>3.2保存角色
            $authManager->add($roles);
            //>>为角色关联权限
            foreach($this->permissions as $permission){
                $authManager->addChild($roles,$authManager->getPermission($permission));
            }
        }else{
            //>>编辑角色
            //>>保存描述信息
            $role->description = $this->description;
            //>>更新角色到数据表
            $authManager->update($role->name,$role);
            //>>更新权限
            //>>删除所有旧的权限
            $authManager->removeChildren($role);
            //>>为角色关联权限
            foreach($this->permissions as $permission){
                $authManager->addChild($role,$authManager->getPermission($permission));
            }
        }
    }

    /**
     * @param $role
     * 加载数据到表单模型
     */
    public function loadFormRole($role){
        $this->name = $role->name;
        $this->description = $role->description;

        //>>3.获取要修改的角色的权限
        $permissions = \Yii::$app->authManager->getPermissionsByRole($role->name);
        $this->permissions = array_keys($permissions);
    }
}