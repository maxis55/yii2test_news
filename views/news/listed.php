<?php

use yii\helpers\Html;
use yii\widgets\ListView;


/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'News List';
$this->params['breadcrumbs'][] = $this->title;
$temp=10;
$this->registerJs(<<<JS
    $('#more').on('click', function (e) {
       e.preventDefault();
       var model=$('#news_listview_id');
       var pubdate=$('.pubdate' ).last().html();
        $.ajax({
            url: '?r=news%2Fmore',
            type: 'GET',
            dataType:'json',
            data: {
                pubdate:pubdate,
                amount:2
            },
            success: function(data){
                 if(data.hasOwnProperty('fail')){
                     alert(data['fail']);
                }else{
                    var i;
                    for (i = 0; i < data.length; ++i) {
                        $('<div>',{
                            'html':'<h2>'+data[i]['title']+'</h2>' +
                            '<p>'+ data[i]['description']+'</p>'+
                            '<p class="pubdate">'+ data[i]['pubdate']+'</p>'+
                            '<img src="dbimages/'+data[i]['img_path']+'" height="280px">'+
                            '<p><a href='+data[i]['link']+'>Link</a></p>'
                        }).appendTo(model);    
                    } 
                }
            }
        });
    });
JS
);
?>
<div class="news-indexx" xmlns="http://www.w3.org/1999/html">

    <h1><?= Html::encode($this->title) ?></h1>

<!--    --><?php //Pjax::begin();?>

    <?php
    echo ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => '_item',
        'summary'=>'',
        'id' => 'news_listview_id'
]);
    ?>
    <p>
    <?= Html::a('Show more', ['#'], ['class' => 'btn btn-success','id'=>'more']) ?>
    </p>
<!--    --><?php //Pjax::end();?>
</div>
