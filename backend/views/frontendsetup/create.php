<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\FrontendSetup */

$this->title = Yii::t('backend','FRONTENDSETUP');
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend','CREATEFEILD'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="frontend-setup-create patern">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
