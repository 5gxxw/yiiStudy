<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "admin".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $salt
 * @property string $email
 * @property string $token
 * @property integer $token_create_time
 * @property integer $add_time
 * @property integer $last_login_time
 * @property string $last_login_ip
 */
class Admin extends \yii\db\ActiveRecord implements IdentityInterface
{
    //>>1.保存验证码
    public $captcha;

    public $repassword;

    public $roles; //保存用户所关联的角色

    /**
     * 定义注册的场景
     */
    const SCENARIO_ADD = 'add';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['token_create_time', 'add_time', 'last_login_time'], 'integer'],
            [['username'], 'string', 'max' => 50],
            [['password', 'token'], 'string', 'max' => 100],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'string', 'max' => 30],
            [['last_login_ip'], 'string', 'max' => 15],
            [['username'], 'unique', 'message' => '用户名已存在'],
            [['email'], 'unique', 'message' => '邮箱已存在'],
            [['email'], 'email'],
            ['captcha','captcha'],
            ['repassword','compare','compareAttribute' => 'password', 'message' => '两次密码不一致'],
            [['password'],'required','on'=>self::SCENARIO_ADD],//添加用户的时候必须填写密码
            [['roles'],'safe'],//角色验证
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'password' => '密码',
            'salt' => '盐',
            'email' => '邮箱',
            'token' => '自动登录令牌',
            'token_create_time' => '令牌创建时间',
            'add_time' => '注册时间',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'captcha' => '验证码',
            'auth_key' => '认证键',
            'repassword' => '确认密码',
            'roles' => '选择角色',
        ];
    }



    /**
     * Finds an identity by the given ID.
     * @param string|integer $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        // 传入id，需要通过id到数据库查找用户
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|integer an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        //返回可以唯一标识用户标识的id。
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return boolean whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key == $authKey;
    }

    /**
     * 获取所有的角色选项
     */
    public static function getRolesOption(){
        //>>实例化rbac
        $authManager = Yii::$app->authManager;
        //>>获取所有角色
        $roles = $authManager->getRoles();
        return ArrayHelper::map($roles,'name','description');
    }

    /**
     * 根据当前登录用户显示菜单
     */
    public function getMenuItems(){
        //>>定义一个数组用于保存返回的数据
        $menuItems = [];
        //>>获取所有一级菜单
        $menus = Menu::find()->where(['parent_id'=>0])->all();
        //>>1.遍历出所有一级菜单
        foreach($menus as $menu) {
            //>>保存返回的二级菜单数据
            $items = [];
            //>>遍历二级菜单,通过一对多关联一级菜单和二级菜单,一级菜单调用关联的方法,返回一个二维数组,遍历该二维数组就是二级菜单
            foreach ($menu->menus as $child) {
                //>>使用can()方法,查看用户是否有该权限
                if (Yii::$app->user->can($child->url)) {
                    $items[] = ['label' => $child->name, 'url' => [$child->url]];
                }
            }
            if(!empty($items)){
                $menuItems[] = ['label'=>$menu->name,'items'=>$items];
            }
        }
        return $menuItems;
    }

    /**
     * 保存添加用户信息
     */
    public function saveLog(){

            //>>加密密码
            $this->password = \Yii::$app->security->generatePasswordHash($this->password);
            //>>生成随机认证的标识
            $this->auth_key = \Yii::$app->security->generateRandomString();

        if ($this->scenario == self::SCENARIO_ADD){
            //注册时间,最后登录时间,最后登录ip
            $this->add_time = time();
            $this->last_login_time = time();
            $this->last_login_ip = \Yii::$app->request->userIP;
        }
        //>>保存
        if($this->save(false)){
            return true;
        }
    }

    /**
     * 保存用户关联的角色
     */
    public function saveRole(){
        //>>实例化rbac
        $authManager = \Yii::$app->authManager;
        //>>获取用户的角色,然后给用户关联角色
        foreach($this->roles as $role){
            $authManager->assign($authManager->getRole($role),$this->id);
        }
    }

    /**
     * 编辑时获取旧的用户角色
     */
    public function getOldRole(){
        //根据id获取旧的角色
        $user_roles = \Yii::$app->authManager->getRolesByUser($this->id);
        $this->roles = array_keys($user_roles);
    }
}
