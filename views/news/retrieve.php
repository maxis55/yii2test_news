<?php


/* @var $this yii\web\View */
/* @var $model app\models\RssSourceForm */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$this->title = 'Retrieve Data For News Table';
?>


<div class="news-retrieve">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->session->hasFlash('rssDataRetrieved')): ?>
    <div class="alert alert-success">
        Rss data retrieved. <?php echo Yii::$app->session->getFlash('rssDataRetrieved'); ?>
    </div>
    <?php else: ?>
    <p>Please fill out the following fields to retrieve information from rss:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'retrieve-form',
        'layout' => 'horizontal',
    ]); ?>

    <?= $form->field($model, 'link')->textInput(['autofocus' => true,'value'=>'https://sport.sme.sk/rss']) ?>


    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Retrieve', ['class' => 'btn btn-primary', 'name' => 'retrieve-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <?php endif; ?>
</div>