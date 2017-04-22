<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/13
 * Time: 14:18
 */

namespace frontend\controllers;


use backend\models\Goods;
use frontend\models\Site;
use yii\web\Controller;
use frontend\models\Cart;


class CartController extends Controller
{
    public $layout = 'cart';
    public $enableCsrfValidation = false;   //关闭csrf验证
    /**
     * 加入购物车
     *      分两种情况
     *      1.未登陆用户->保存到cookie中
     *      2.已登陆用户->保存到数据表
     *      cookie存在于两个组件中
     */
    public function actionCart(){
        //>>1.接收post传入的goods_id和num
        $data = \Yii::$app->request->post();
        $goods_id = $data['goods_id'];
        $num = $data['num'];
        //>>判断是否登陆
        if(\Yii::$app->user->isGuest){
            //>>判断cookie中是否有该商品,如果有就累加,没有就添加
            //>>实例化操作购物车cookie的组件
            $cart = \Yii::$app->cartCookieHandler;
            //>>检测cookie是否存在
            $cart->check($goods_id,$num);
            //>>添加到cookie中
            $cart->save();

        }else{
            //>>已经登陆状态
            //>>1.获取当前登录用户的id
            $member_id = \Yii::$app->user->id;
            //>>根据用户id和商品id将数据表的购物车信息读取出来
            $member_cart = Cart::findOne(['and','member_id'=>$member_id,'goods_id'=>$goods_id]);

            //>>如果数据库有该商品,则直接累加,否则直接添加
            if ($member_cart){
                $member_cart->num += $num;
                $member_cart->save();
            }else{
                $member_cart = new Cart();
                $member_cart->goods_id = $goods_id;
                $member_cart->member_id = $member_id;
                $member_cart->num = $num;
                $member_cart->save();
            }
        }
        //直接跳转到购物车
        return $this->redirect(['cart/carts']);
    }

    /**
     * 进入购物车,显示购物车
     */
    public function actionCarts()
    {
        $models =[];
        if (\Yii::$app->user->isGuest){
            //>>从cookie中取出商品id,根据商品id查询出商品信息并显示
            $cartCookie = \Yii::$app->cartCookieHandler;
            foreach($cartCookie->cart as $goods_id => $num){
                $good = Goods::findOne(['id'=>$goods_id])->toArray();
                $good['num'] = $num;
                $models[] = $good;
            }
        }else{
            //>>如果已经登录用户,从数据库中查询出该用户的购物车信息
            $carts = Cart::find()->where(['member_id'=>\Yii::$app->user->id])->all();
            foreach($carts as $cart){
                $good = Goods::findOne(['id'=>$cart->goods_id])->toArray();
                $good['num']=$cart->num;
                $models[] = $good;
            }
        }
        //>>根据商品id查询出商品信息
        return $this->render('cart',['models'=>$models]);
    }

    public function actionAjax($filter){
        switch ($filter){
            case 'modify':
                //>>接收ajax传过来的post数据,商品id,数量
                $goods_id = \Yii::$app->request->post('goods_id');
                $num = \Yii::$app->request->post('num');
                if (\Yii::$app->user->isGuest){//>>未登陆
                    //>>获取cookie中的购物车数据
                    \Yii::$app->cartCookieHandler->edit($goods_id,$num)->save();
                }else{
                    //登陆用户
                    //>>根据id查询出购物车数据
                    $cart = Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id]);
                    //>>将num修改,保存
                    $cart->num = $num;
                    $cart->save();
                }
                return 'success';
                break;

            case 'del':
                //>>删除购物车数据
                //>>获取post请求传过来的商品id,根据商品id删除购物车的数据
                $goods_id = \Yii::$app->request->post('goods_id');
                if (\Yii::$app->user->isGuest){
                    \Yii::$app->cartCookieHandler->del($goods_id)->save();
                }else{
                    //>>已经登录

                }
                return 'success';
                break;
        }
    }
}