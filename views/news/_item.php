<?php
    use yii\helpers\Html;
    use yii\helpers\HtmlPurifier;
    ?>

<h2><?= /** @var \app\models\News $model */
    Html::encode($model->title) ?></h2>
<p>
    <?= HtmlPurifier::process($model->description) ?>
</p>

<p class="pubdate">
    <?= HtmlPurifier::process($model->pubdate) ?>
</p>

    <?= Html::img('dbimages/'.$model->img_path,['height'=>'280px']);?>
<p>
    <?= Html::a('Link',$model->link) ?>
</p>
