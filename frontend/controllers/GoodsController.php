<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use shop\Services\GoodsService;

class GoodsController extends Controller
{
	public $service;

  public function __construct($id, $module, GoodsService $service, array $config = [])
  {
      parent::__construct($id, $module, $config);
      $this->service=$service;
  }
	public function actionCartgoodsprice(){
      $size = $_POST['size'];
      $idGoods = $_POST['idGoods'];
      $priceResult = $this->service->cartGoodsPrice($size, $idGoods);
      return $priceResult;
  }
  public function actionCartgoods(){
      if (Yii::$app -> request -> get('id')) {
      		$id = Yii::$app -> request -> get('id');
      		$good = $this->service->cartGoods($id);
          return $this -> render('cartgoods', ['good' => $good]);
      }
  }
  public function actionSearchgoods(){
   	if (Yii::$app->request->post()){
       	$namegoods = Yii::$app->request->post('goods');
       	$goods = $this->service->searchGoods($namegoods); 
       	return $this->render('searchgoods', ['goods' => $goods, 'namegoods' => $namegoods]);
   	}
  }
  public function actionGoods(){
        if (Yii::$app -> request -> get('id')) {
        		$id = Yii::$app -> request -> get('id');
        		$good = $this->service->goodsCategory($id);
            return $this -> render('goods', ['goods' => $good]);
        }
    }
}