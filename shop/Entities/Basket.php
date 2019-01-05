<?php

namespace shop\Entities;

use common\model\Goods;

/**
 * This is the model class for table "basket".
@@ -24,9 +24,23 @@
 * @property string $comments
 * @property int $price
 * @property int $confirmed
 *
 * @property Goods $goods
 */
class Basket extends \yii\db\ActiveRecord
{
    public static function create(int $idGoods,int $userId,int $price):self
    {
        $bascet=new static();
        $bascet->id_goods = $idGoods;
        $bascet->id_user = $userId;
        $bascet->active = '1';
        $bascet->amount = '1';
        $bascet->confirmed = '0';
        $bascet->price = $price;
        return $bascet;
    }
    /**
     * {@inheritdoc}
     
@@ -72,8 +86,9 @@ 
*/
    public function attributeLabels(){
        [
            'confirmed' => 'Confirmed',
        ];
    }
    /*public function getGoods()
    {
        return $this->hasOne(Goods::class, ['id' => 'id_goods']);
    }*/
}