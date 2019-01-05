<?php

namespace shop\Repositories;

use common\model\Goods;
use rmrevin\yii\fontawesome\component\UnorderedList;
use shop\Entities\Basket;

class GoodsRepository
{
    public function getGoodsToBasket($goodsId):array
    {
        return Goods::find()->with(['article', 'image', 'price', 'size'])->where(['id' => $goodsId])->asArray()->all();
    }
}