<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/2
 * Time: 10:22
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    //>>指定属性
    public $username;
    public $password;
    //>>1.保存验证码
    public $captcha;
    //>>记住我
    public $rememberMe;

    //>>验证
    public function rules(){
        return [
            [['username','password'],'required'],
            [['username'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 32],
            ['captcha','captcha'],
            ['rememberMe','safe']
        ];
    }
    //>>指定字段名称
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'captcha' => '验证码',
            'rememberMe' => '记住我',
        ];
    }

    /**
     * 登录验证
     */
    public function Login(){
        if($this->validate()) {
            //>>1.根据用户名查找用户信息
            $admin = Admin::find()->where(['or',['=','username',$this->username],['=','email',$this->username]])->one();
            if ($admin) {
                //>>2.验证用户密码
                if (\Yii::$app->security->validatePassword($this->password, $admin->password)) {
                    $admin->last_login_time = time();
                    $admin->last_login_ip = \Yii::$app->request->userIP;
                    //>>判断是否选择自动登录
                    if($this->rememberMe){
                        //登录,第二个参数传持续时间
                        \Yii::$app->user->login($admin,3600*24*7);
                        return true;
                    }
                    //如果没有选择记住我,就走这里登录
                    \Yii::$app->user->login($admin);
                    return true;
                } else {
                    //密码错误
                    $this->addError('password', '密码错误');
                }
            } else {
                //>>验证失败,用户名不存在
                $this->addError('username', '用户名不存在');
            }
        }
        return false;
    }
}