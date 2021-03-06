<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\Message */

$this->title = Yii::t('backend','NEW_MESSAGE');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','MESSAGE'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="message-create patern">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
