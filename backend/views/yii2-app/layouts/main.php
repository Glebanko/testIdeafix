<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

if (Yii::$app->controller->action->id === 'login') {
/**
 * Do not use this code in your template. Remove it.
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {


        backend\assets\AppAsset::register($this);


    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body class='hold-transition skin-blue sidebar-mini <?= isset($this->params['body-class'])?$this->params['body-class']:''; ?>'>
    <?php $this->beginBody() ?>
    <?php $this->beginBody() ?>
    <div class="wrapper <?= isset($this->params['body-class']) ? $this->params['body-class'] : '' ?>">
    <?php
    if(Yii::$app->user->can('userCanManager')){ ?>
        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>
        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>
        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>
    <?php  }else{ ?>
      <?= $this->render(
          'notexit.php'
        )?>

    <?php } ?>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php
} ?>
