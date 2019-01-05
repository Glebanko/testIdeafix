<?php

namespace shop\Services;

use shop\Entities\Basket;
use common\model\Basket as basketModel;
use shop\Forms\BasketForm;
use shop\Repositories\BasketRepository;
use shop\Repositories\GoodsRepository;

class BasketService
{
    public $goodsRepository;
    public $basketRepository;
    
    public function __construct(GoodsRepository $goodsRepository,BasketRepository $basketRepository)
    {
        $this->goodsRepository=$goodsRepository;
        $this->basketRepository=$basketRepository;
    }
    public function IsGuest($goodsCookie)
    {
        $amount = count($goodsCookie['goods']);
        $goodsId=[];
        $goodsToCookie=[];
        foreach ($goodsCookie['goods'] as $good) {
            $goodsId[] = $good['idGoods'];
            $goodsToCookie[] = $good;
        }
        $goods=$this->goodsRepository->getGoodsToBasket($goodsId);
        return [
            'goods' => $goods,
            'goodsId' => $goodsId,
            'goodsToCookie' => $goodsToCookie,
        ];
    }
    public function usertIdentity()
    {
        return['goods'=>$this->basketRepository->notConfirmed()];
    }
    public function create(BasketForm $form,int $userId): void
    {
        $basket= Basket::create($form->id,$userId,$form->price);
        $this->basketRepository->save($basket);
    }
    public function amountGoods($idUser){
        $amountbasket = $this->basketRepository->amountGoods($idUser);
        $amount = count($amountbasket);
        return $amount;
    }
    public function searchCookie($goods){
        if(isset($goods['goods']) and $goods['goods'][0] != null){
           $amount = count($goods['goods']);
           return $amount;
        }else{
            return 0;
        }
    }
    public function goBasketCookieIsGuest($idGoods, $color, $size, $price, $goodsCookie){
        if(empty($goodsCookie['goods'][0])){
            unset($goodsCookie);
            $amount = 0;
        }else{
            $amount = count($goodsCookie["goods"]);
        }
        $goodsCookie['goods'][$amount]['idGoods'] = $idGoods;
        $goodsCookie['goods'][$amount]['price'] = $price;
        $goodsCookie['goods'][$amount]['amount'] = 1;
        if($color == null){
            $goodsCookie['goods'][$amount]['color'] = "Не указан";
        }else{
            $goodsCookie['goods'][$amount]['color'] = $color;
        }
        if($size == null){
            $goodsCookie['goods'][$amount]['size'] = "Не указан";
        }else{
            $goodsCookie['goods'][$amount]['size'] = $size;
        }
        setcookie('goods', serialize($goodsCookie));
    }
    public function goBasketCookieIsUser($idGoods, $color, $size, $price, $idUser){
        $model = new basketModel;
                $model -> id_goods = $idGoods;
                $model -> id_user = $idUser;
                $model -> active = '1';
                $model -> amount = '1';
                $model -> confirmed = '0';
                $model -> color = $color;
                $model -> size = $size;
                $model -> price = $price;
                $model -> save();
    }
    public function basketRequestIsGuest($name, $surname, $email, $phone, $city, $deliveryAddress, $postOffice, $comments, $id_goods, $amount, $price, $color, $size){
        $model = new basketModel;
                    $model -> name = $name;
                    $model -> surname = $surname;
                    $model -> email = $email;
                    $model -> phone = $phone;
                    $model -> city = $city;
                    $model -> deliveryAddress = $deliveryAddress;
                    $model -> postOffice = $postOffice;
                    $model -> comments = $comments;
                    $model -> id_goods = $idGoods;
                    $model -> id_user = '0';
                    $model -> active = '0';
                    $model -> amount = $amount;
                    $model -> confirmed = '1';
                    $model -> price = $price;
                    $model -> color = $color;
                    $model -> size = $size;
                    $model -> save();
    }
    public function basketRequestIsUser($name, $surname, $email, $phone, $city, $deliveryAddress, $postOffice, $comments, $idUser){
        $models = $this->basketRepository->basketRequestIsUser($idUser);
        foreach ($models as $model) {
            $model -> name = $name;
            $model -> surname = $surname;
            $model -> email = $email;
            $model -> phone = $phone;
            $model -> city = $city;
            $model -> deliveryAddress = $deliveryAddress;
            $model -> postOffice = $postOffice;
            $model -> comments = $comments;
            $model -> confirmed = '1';
            $model -> active = '0';
            $model -> save();
        }
    }
    public function massiveCookie($goodsCookie){
        $emptyCookie['goods'][] = null;
        if(empty($goodsCookie)){
           setcookie('goods', serialize($emptyCookie));
        }
    }
    public function deleteBasketFormIsGuest($keyGoods){
       $goodsCookie = unserialize($_COOKIE['goods']);
       unset($goodsCookie['goods'][$keyGoods]);
       $newArray['goods'] = array_values($goodsCookie['goods']);
       setcookie('goods', serialize($newArray));
    }
    public function deleteBasketFormIsUser($idGoods){
        $goods = basketModel::find()->where(['id_goods' => $idGoods])->one();
        $goods->delete();
    }
}