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
    public function searchCartGoodsPrice($idGoods):array
    {
        return Goods::find()->with(['size', 'price'])->where(['id' => $idGoods]) -> asArray() -> one();
   	}
   	public function searchCartGoods($id):array
   	{
   			return Goods::find()->with(['article', 'image', 'price', 'size', 'color', 'info'])->where(['slug_gods' => $id]) -> asArray() -> one();
   	}
   	public function searchGoods($namegoods):array
   	{
   		return Goods::find()->with(['article', 'image', 'price', 'size'])->andFilterWhere(['like', 'title', $namegoods])->asArray()->all();
   	}
}