<?php

/**
 * 处理购物车cookie
 */
namespace frontend\components;

use yii\web\Cookie;

class CartCookieHandler extends \yii\base\Component
{
    public $cart = [];   //>>保存到cookie的数据

    //>>获取cookie
    public function __construct()
    {
        $cookies = \Yii::$app->request->cookies;
        //>>键为自定义的键cart,值为保存的序列化的字符串
        $cookie = $cookies->get('cart');
        //>>如果为空,表示cookie中之前没有保存数据
        if ($cookie != null){   //创建这个变量
            //>>取出之前保存的数据
            $this->cart = unserialize($cookie->value);
        }
        parent::__construct();

    }

    /**
     * 保存cookie
     */
    public function Save(){
        //>>重新保存到cookie中
        $cookies = \Yii::$app->response->cookies;
        $cookie = new Cookie([
            'name' => 'cart',
            'value' => serialize($this->cart),
        ]);
        $cookies->add($cookie);
    }

    /**
     * 修改cookie
     */
    public function Edit($goods_id,$num){
        $this->cart[$goods_id] = $num;

        return $this;
    }

    /**
     * 删除cookie
     */
    public function Del($goods_id){
        if ($this->cart[$goods_id]){
            //>>清除cookie中的该商品id数据
            unset($this->cart[$goods_id]);
        }
        return $this;
    }


    /**
     * 查看cookie
     * @param $goods_id->商品id
     * @param $num->数量
     */
    public function Check($goods_id,$num){
        //>>判断之前保存到cookie的数据中是否已经有本次的商品id
        if (array_key_exists($goods_id,$this->cart)){
            //>>存在,数量累计
            $this->cart[$goods_id] += $num;
        }else{
            //>>不存在,直接添加
            $this->cart[$goods_id] = $num;
        }
        return $this;
    }
}