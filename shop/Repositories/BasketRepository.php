<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26.12.18
 * Time: 23:47
 */
namespace Shop\Repositories;
use shop\Entities\Basket;
use common\model\Basket as basketModel;
class BasketRepository
{
    public function notConfirmed():array
    {
        return basketModel::find()
            ->with(['goods', 'goods.article', 'goods.image', 'goods.price', 'goods.size'])
            ->where(['confirmed' => '0'])
            ->asArray()
            ->all();
    }
    public function amountGoods($idUser):array
    {
        return basketModel::find()->where(['id_user' => $idUser])->where(['active' => '1'])->asArray()->all();
    }
    public function basketRequestIsUser($idUser):array
    {
        return basketModel::find()->where(['id_user'=>$idUser])->all();
    }
    public function save(Basket $basket):Basket
    {
        if(!$basket->save()){
            throw new \RuntimeException(var_dump($basket->errors));
        }
        return $basket;
    }
}