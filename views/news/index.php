<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'News';
$this->params['breadcrumbs'][] = $this->title;
$temp=10;
$this->registerJs(" $(document).ready(function(e) {
       var table = $('#newsGrid table tbody'); 

       var rows = table.find('tr');
       var rowNum = rows.size(); 
       var columnsNum = $(rows[0]).find('td').size(); 

       for(var i = 0; i < rowNum; i++) {
           var row = $(rows[i]);
//           $(row).after('<tr><td colspan='+ columnsNum +'>Lore Ipsum</td></tr>');
//           $(row).after('<tr><td>Lore Ipsum</td><td>Lore Ipsum</td><td>Lore Ipsum</td><td>Lore Ipsum</td><td>Lore Ipsum</td><td>Lore Ipsum</td><td>Lore Ipsum</td><td>Lore Ipsum</td></tr>');
       }   
//       var lastRow= rows[rows.size()-1];
//            $(lastRow).after('<tr><td colspan='+ columnsNum +'>New Rows</td></tr>');
    });");
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php Pjax::begin();?>
    <?=GridView::widget([
            'id'=>'newsGrid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterSelector' => '#' . Html::getInputId($searchModel, 'pagesize'),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'title',
            'description',
            'pubdate',
            [
                'attribute' => 'img',
                'format' => 'html',
                'label' => 'Img',
                'value' => function ($data) {
                    return Html::img('dbimages/'.$data['img_path'],
                        ['width' => '60px']);
                },
            ],

            'link',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);?>
    <?php Pjax::end();?>
</div>
