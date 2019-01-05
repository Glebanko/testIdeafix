<?php

namespace common\models;

use common\models\mongo\Products;
use Yii;
use backend\models\Translit;
use yii\helpers\FileHelper;
/**
 * This is the model class for table "abh_image".
 *
 * @property integer $id
 * @property integer $id_gods
 * @property integer $id_post
 * @property integer $id_cat
 * @property integer $id_page
 * @property string $path
 * @property string $name
 */
class Image extends \yii\db\ActiveRecord
{
    public $id_goods;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'abh_image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_gods', 'id_post', 'id_cat','id_page','forHome','forFancy'], 'integer'],
            [['path', 'name'], 'required'],
            [['path', 'name'], 'string', 'max' => 610],
        ];
    }
    public function beforeDelete()
    {
        if( file_exists(Yii::getAlias('@frontend') . '/web/image/'. $this->path .  $this->name))
            unlink(Yii::getAlias('@frontend') . '/web/image/'. $this->path .  $this->name);
        if( file_exists(Yii::getAlias('@frontend') . '/web/image/'. $this->path .'recomended-'.  $this->name))
            unlink(Yii::getAlias('@frontend') . '/web/image/'. $this->path .'recomended-'.  $this->name);
        if( file_exists(Yii::getAlias('@frontend') . '/web/image/'. $this->path .'avatar-'.  $this->name))
            unlink(Yii::getAlias('@frontend') . '/web/image/'. $this->path .'avatar-'.  $this->name);

        return parent::beforeDelete(); // TODO: Change the autogenerated stub
    }

   /* public function beforeSave($insert)
    {
        $product=Products::find()->where(['product_id'=>(int)$this->id_gods])->one();
        if($product){
            $count=Image::find()->where(['id_gods'=>$this->id_gods])->count();
            $image=$this->path.$this->name;
            $add=$product->image;
            if($this->forHome ==1){
                $add['forHome']= $image;
            }else if($this->forFancy ==1){
                $add['forFancy']=$image;
            }else{
                $add['outher'][$count+1]=$image;
            }
            $add['avatar'][$count+1]=$this->path.'avatar-'.$this->name;
            $product->image = $add;
            $product->save();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }*/

    public function baseSave($basePath,$field,$id,$name){
        $transliterator= new Translit();
        $bp = array();
        if(preg_match('/,%,/',$name)){
            $mathes=explode(',%,',$name);
            foreach ($mathes as $math){
                $bp[] = $basePath;
                $names=$transliterator->traranslitImg($math);
                $theImage= Image::findOne(['path'=>$basePath,$field=>$id,'name'=>$name ]);
                if(!isset($theImage)){
                    switch ($field){
                        case 'id_gods':
                            $imageModel= new Image([
                                'path'=>$basePath,
                                'id_gods'=>$id,
                                'name'=>$names,
                                'forHome'=>1,
                                'forFancy'=>1
                            ]);
                        break;
                        case 'id_post':
                            $imageModel= new Image([
                                'path'=>$basePath,
                                'id_post'=>$id,
                                'name'=>$names
                            ]);
                        break;
                        case 'id_cat':
                            $imageModel= new Image([
                                'path'=>$basePath,
                                'id_cat'=>$id,
                                'name'=>$names
                            ]);
                        break;
                        default:
                            $imageModel= new Image([
                                'path'=>$basePath,
                                'id_page'=>$id,
                                'name'=>$names ]);
                    }
                    $imageModel->save();
                }
            }
        }else{
            $names=$transliterator->traranslitImg($name);
            $theImage= Image::findOne(['path'=>$basePath,$field=>$id,'name'=>$name ]);
            if(empty($theImage)) {
                switch ($field){
                    case $field=='id_gods':
                        $imageModel= new Image([
                            'path'=>$basePath,
                            'id_gods'=>$id,
                            'name'=>$names,
                            'forHome'=>1,
                            'forFancy'=>1
                        ]);
                        break;
                    case $field=='id_post':
                        $imageModel= new Image([
                            'path'=>$basePath,
                            'id_post'=>$id,
                            'name'=>$names
                        ]);
                        break;
                    case $field=='id_cat':
                        $imageModel= new Image([
                            'path'=>$basePath,
                            'id_cat'=>$id,
                            'name'=>$names
                        ]);

                        break;
                    default:
                        $imageModel= new Image([
                            'path'=>$basePath,
                            'id_page'=>$id,
                            'name'=>$names ]);
                }
                $imageModel->save();

            }
            return $theImage;
        }

    }
    public function beforeSave($insert)
    {
        if($this->forHome==null)
            $this->forHome=0;
        if($this->forFancy)
            $this->forFancy=0;
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    /**
     * Saves an existing Image model.
     * @param integer $id
     * @param object $model
     * @return mixed
     */
    public function saveimg($id,$basePath,$filename,$forHome,$forFancy,$feild=null){
        $transliterator= new Translit();
        $errSave=array();
        if(preg_match('/,%,/',$filename)) {
            $mathes = explode(',%,', $filename);
            //$names = $transliterator->traranslitImg($filename);
            if (isset($feild)) {
                $imageArray=array();
                foreach ($mathes as $math) {
                    str_replace(' ','',$math);
                    $names = $transliterator->traranslitImg($math);
                    $imageArray[] = ([$basePath, $id, $names, $forHome, $forFancy]);
                }
                Yii::$app->db->createCommand()->batchInsert('abh_image', ['path', $feild, 'name', 'forHome', 'forFancy'],$imageArray)->execute();
            }
            $errSave[]=$feild;
        }else{
            $names = $transliterator->traranslitImg($filename);
            if (isset($feild)) {
                    $image=Image::findOne([$feild=>$id,'name'=>$names]);
                    if(empty($image)){
                        $images=new Image(['path'=>$basePath,$feild=>$id,'name'=>$names,'forHome'=>$forHome,'forFancy'=>$forFancy]);
                        $images->save();
                    }else{
                        $image->path=$basePath;
                        $image->$feild=$id;
                        $image->name=$names;
                        $image->forHome=$forHome;
                        $image->forFancy=$forFancy;
                        $image->save();
                    }
            }
            $errSave[]=$feild;
        }
        return $errSave;
    }
     public function getGoods(){
         return $this->hasOne(Gods::className(),['id'=>'id_gods']);
     }
     public function getPost(){
         return $this->hasOne(Post::className(),['id'=>'id_post']);
     }
     public function getCat(){
         return $this->hasOne(Category::className(),['id'=>'id_cat']);
     }
     public function getPage(){
         return $this->hasOne(Page::className(),['id'=>'id_page']);
     }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_gods'   =>  'Id Gods',
            'id_post'   =>  'Id Post',
            'id_cat'    =>  'Id Cat',
            'id_page'   =>  'Id Page',
            'path'      =>  'Path',
            'name'      =>  'Name',
            'forHome'   =>  'For Home',
            'forFancy'  =>  'For Fancy'
        ];
    }
}
