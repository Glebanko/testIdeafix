<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\FrontendSetup */

$this->title = Yii::t('backend','MENUPDATA').': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','MENUSETUP'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('backend','UPDATE');
?>
<div class="frontend-setup-update patern">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'category'  =>  $category,
        'pages' =>  $pages,
        'value'     =>  $value
    ]) ?>

</div>
