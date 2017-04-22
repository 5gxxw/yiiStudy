<?php

namespace frontend\controllers;

use frontend\models\Cart;
use frontend\models\Member;
use frontend\models\MemberForm;

use Flc\Alidayu\Client;
use Flc\Alidayu\App;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;


class MemberController extends \yii\web\Controller
{
    //>>定义布局文件
    public $layout = 'login';

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 用户注册
     */
    public function actionRegister(){

        //>>1.实例化
        $model = new Member();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //>>调用方法保存数据到数据库
            if ($model->add()){
                \Yii::$app->session->addFlash('success','注册成功');
                return $this->redirect(['member/index']);
            }
        }
        return $this->render('register',['model'=>$model]);
    }

    /**
     * 发送短信验证码
     */
    public function actionSms(){
        //>>1.接收手机号码
        $tel = \Yii::$app->request->post('tel');

        //>>限制用户手机号码发送短信的次数
        //>>设计表(id,手机号码,次数,天,时间)
        //>>判断是否有该手机号码的记录,如果没有,创建一条记录
        //>>如果有,判断该手机号码是否是当天的记录,如果不是,修改天,次数,时间
        //>>如果是当天的记录,再判断次数是否大于5次,如果大于5次,则当天不能再发送验证码,如果小于5次,可以发送验证码,
        //>>判断当前时间-上次发送短信的时间,是否超过5分钟,如果不超过5分钟,将上次的验证码发送给用户


        //>>2.生成短信验证码
        $code = rand(1000,9999);
        //>>3.保存验证码到session
        \Yii::$app->session->set('tel_'.$tel,$code);
        //>>4.发送短信验证码
        // 配置信息
        $config = [
            'app_key'    => '23742906',
            'app_secret' => '3ac3e878ff3db712874d2adc28c35c44',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];
        // 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;
        $req->setRecNum($tel)       //>>发送到哪个手机号码
            ->setSmsParam(['content' => $code]) //>>验证码
            ->setSmsFreeSignName('5g学习网')   //>>短信签名
            ->setSmsTemplateCode('SMS_60775139');   //>>模板id
        $resp = $client->execute($req);

        return true;
    }


    /**
     * 用户登录
     */
    public function actionLogin(){
        //>>1.实例化
        $model = new MemberForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            //>>调用方法帮我登录
            if($model->memberLogin()){
                //>>登录成功
                //>>读取cookie中的购物车信息
                $cookies = \Yii::$app->request->cookies;
                $cookie = $cookies->get('cart');
                //>>如果cookie中有数据,将数据保存到数据表
                if($cookie != null){
                    //>>读取cookie的数据
                    $carts = unserialize($cookie->value);
                    $member_id = \Yii::$app->user->id;
                    foreach($carts as $goods_id => $num){
                        $member_cart = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>$member_id]);
                        //>>如果有,数量累加,如果没有,直接添加
                        if ($member_cart){
                            $member_cart->num += $num;
                            $member_cart->save();
                        }else{
                            $member_cart = new Cart();
                            $member_cart->num = $num;
                            $member_cart->goods_id = $goods_id;
                            $member_cart->member_id = $member_id;
                            $member_cart->save();
                        }
                    }
                    //>>清除cookie
                    \Yii::$app->response->cookies->remove('cart');
                }

                \Yii::$app->session->addFlash('success','登录成功');

                return $this->redirect([\Yii::$app->user->getReturnUrl(['index/index'])]);
            }
        }
        return $this->render('login',['model'=>$model]);
    }

    public function actionGuest(){
        var_dump(\Yii::$app->user->isGuest);
    }

    /**
     * 注销
     */
    public function actionLogout(){
        //>>掉用
        \Yii::$app->user->logout();

    }

    public function actionTest(){
        // 配置信息
        $config = [
            'app_key'    => '23742906',
            'app_secret' => '3ac3e878ff3db712874d2adc28c35c44',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];

        // 使用方法一
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;

        $req->setRecNum('15298245557')
            ->setSmsParam([
                'content' => rand(100000, 999999)
            ])
            ->setSmsFreeSignName('5g学习网')
            ->setSmsTemplateCode('SMS_60775139');

        $resp = $client->execute($req);
        var_dump($resp);
    }
}
