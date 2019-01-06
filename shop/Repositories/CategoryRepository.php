<?php

namespace shop\Repositories;

use common\model\Category;

class CategoryRepository
{
    public function goodsCategory($id):array
    {
        return Category::find()->with(['goods.article', 'goods.image', 'goods.price', 'goods.size', 'parent'])->where(['slug_category' => $id]) -> asArray() -> one();
    }
}