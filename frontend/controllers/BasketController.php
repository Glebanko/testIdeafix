<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use shop\Entities\Basket;
use shop\Forms\BasketForm;
use shop\Forms\ValidateFormBasket;
use shop\Services\BasketService;

//use frontend\models\validate\validateFormBasket;

/**
 * Site controller
 */
class BasketController extends Controller
{
    public $service;

    public function __construct($id, $module,BasketService $service, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service=$service;
    }

    /**
     * {@inheritdoc}
     */

    public function actionBasket(){
        $form = new ValidateFormBasket();
        $form->load(Yii::$app->request->post());

        if(Yii::$app->user->isGuest) {
            $goodsCookie = unserialize($_COOKIE['goods']);// todo хранить товар в куках плохая мысль используй redis
            $goods = $this->service->IsGuest($goodsCookie);
        }else{
            $goods =  $this->service->usertIdentity();
        }
        
        return $this->render('basket', ['goods' => $goods, 'model' => $form]);
    }

    public function actionMassivecookie(){
        $goodsCookie = unserialize($_COOKIE['goods']);
        $this->service->massiveCookie($goodsCookie);
    }
    public function actionDeletebasketform(){
        if(Yii::$app->user->isGuest){
            if (Yii::$app->request->post()){
               $keyGoods = Yii::$app->request->post('keyGoods');
               $this->service->deleteBasketFormIsGuest($keyGoods);
               return $this->redirect('basket');
           }
       }else{
            if (Yii::$app->request->post()){
               $idGoods = Yii::$app->request->post('idGoods');
               $this->service->deleteBasketFormIsUser($idGoods);
               return $this->redirect('basket');
           }
       }
    }

   public function actionGobasket(){
        $form = new BasketForm();
        $form->load(Yii::$app->request->post());
        if($form->validate()){
            try{
                $userId = Yii::$app->user->identity->id;
                $this->service->create($form, $userId);
            }catch (\RuntimeException $e){
                Yii::error($e);
                Yii::$app->session->setFlash('error','Корзина не сохраниласьть обратитесь к администратору');
            }
        }
    }

    public function actionBasketrequest(){
        if (Yii::$app->request->post()){
            if(Yii::$app->user->isGuest){
                $goodsCookie = unserialize($_COOKIE['goods']);
                foreach ($goodsCookie['goods'] as $goods) {
                    $name = $_POST['validateFormBasket']['name'];
                    $surname = $_POST['validateFormBasket']['surname'];
                    $email = $_POST['validateFormBasket']['email'];
                    $phone = $_POST['validateFormBasket']['phone'];
                    $city = $_POST['validateFormBasket']['city'];
                    $deliveryAddress = $_POST['validateFormBasket']['deliveryAddress'];
                    $postOffice = $_POST['validateFormBasket']['postOffice'];
                    $comments = $_POST['validateFormBasket']['comments'];
                    $id_goods = $goods['idGoods'];
                    $amount = $goods['amount'];
                    $price = $goods['price'];
                    $color = $goods['color'];
                    $size = $goods['size'];
                    $this->service->basketRequestIsGuest($name, $surname, $email, $phone, $city, $deliveryAddress, $postOffice, $comments, $id_goods, $amount, $price, $color, $size);
                }
                $psevdCookie['goods'][] = null;
                setcookie('goods', serialize($psevdCookie));
                return "
                    <h3 style='text-align: center;'>Спасибо! Ваш заказ принят.</h3>
                    <h3 style='text-align: center;'>В ближайшее время мы свяжемся с Вами!</h3></div>
                ";
            }else{
                $idUser = Yii::$app->user->identity->id;
                $name = $_POST['validateFormBasket']['name'];
                $surname = $_POST['validateFormBasket']['surname'];
                $email = $_POST['validateFormBasket']['email'];
                $phone = $_POST['validateFormBasket']['phone'];
                $city = $_POST['validateFormBasket']['city'];
                $deliveryAddress = $_POST['validateFormBasket']['deliveryAddress'];
                $postOffice = $_POST['validateFormBasket']['postOffice'];
                $comments = $_POST['validateFormBasket']['comments'];
                $this->service->basketRequestIsUser($name, $surname, $email, $phone, $city, $deliveryAddress, $postOffice, $comments, $idUser);
                return "
                    <h3 style='text-align: center;'>Спасибо! Ваш заказ принят.</h3>
                    <h3 style='text-align: center;'>В ближайшее время мы свяжемся с Вами!</h3></div>
                ";
            }

        }
    }
    public function actionGobasketcookie(){
        if(Yii::$app->user->isGuest){
            $idGoods = $_POST['id'];
            $color = $_POST['color'];
            $size = $_POST['size'];
            $price = $_POST['price'];
            $goodsCookie = unserialize($_COOKIE['goods']);

            $this->service->goBasketCookieIsGuest($idGoods, $color, $size, $price, $goodsCookie);
        }else{
            $idGoods = $_POST['id'];
            $color = $_POST['color'];
            $size = $_POST['size'];
            $price = $_POST['price'];
            $idUser = Yii::$app->user->identity->id;

            $this->service->goBasketCookieIsUser($idGoods, $color, $size, $price, $idUser);
        }
    }
    public function actionSearchcookie(){
        $goods = unserialize($_COOKIE['goods']);
        $amount = $this->service->searchCookie($goods);
        return $amount;
    }
    public function actionAmountgoods(){
        $idUser = Yii::$app->user->identity->id;
        $amount = $this->service->amountGoods($idUser);
        return $amount;
    }
}
