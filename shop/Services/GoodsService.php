<?php

namespace shop\Services;

use shop\Entities\GoodsEntities;
use shop\Repositories\BasketRepository;
use shop\Repositories\GoodsRepository;
use shop\Repositories\CategoryRepository;

class GoodsService
{
    public $goodsRepository;
    public $basketRepository;
    public $categoryRepository;
    
    public function __construct(GoodsRepository $goodsRepository,BasketRepository $basketRepository, CategoryRepository $categoryRepository)
    {
        $this->goodsRepository=$goodsRepository;
        $this->basketRepository=$basketRepository;
        $this->categoryRepository=$categoryRepository;
    }
    public function cartGoodsPrice($size, $idGoods){
        $good = $this->goodsRepository->searchCartGoodsPrice($idGoods);

        $prices[] = $good['price']['price1'];
        $prices[] = $good['price']['price2'];
        $prices[] = $good['price']['priceEvro'];
        $prices[] = $good['price']['priceSem'];

        $numberSize = array_search($size, array_column($good['size'], 'value'));
        foreach ($prices as $price) {
            if($price == 0 or $price == null){
                $countPrice++;
            }
        }
        if ($countPrice >= 3) {
            $priceResult = $prices[0];
        }else{
            $priceResult = $prices[$numberSize];
        }
        return $priceResult;
    }
    public function cartGoods($id){
        return $this->goodsRepository->searchCartGoods($id);
    }
    public function searchGoods($namegoods){
        return $this->goodsRepository->searchGoods($namegoods);
    }
    public function goodsCategory($id){
        return $this->categoryRepository->goodsCategory($id);
    }
}