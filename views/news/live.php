<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'News Livesearch';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJs(<<<JS

$('#search-field').on('keyup',function(){
    var key=$(this).val();
    var searchh=$('#search-help');
    if(key.length!=''){
        $.ajax({
            url: '?r=news%2Fsuggest',
            type: 'GET',
            dataType:'json',
            data: {
                keyword:key
            },
            success: function(data){
                searchh.empty();
                if(data.hasOwnProperty('fail')){
                   $('<li>',{
                            'html':'<p>'+data['fail']+'</p>'
                        }).appendTo(searchh); 
                }else{
                     var i;
                    for (i = 0; i < data.length; ++i) {
                        $('<li>',{
                            'html':'<a href="?r=news%2Fview&amp;id='+data[i]['id']+'">'+data[i]['title']+'</a>'
                        }).appendTo(searchh);    
                    } 
                      
                }
                searchh.show();
            }
        });
    }
    else{
        searchh.hide();
    }
    
});
JS
);


?>

<div class="news-search">
    <h1><?= Html::encode($this->title) ?></h1>
<div class="livesearch">
    <div>
        <input title="livesearch" id="search-field" name="search" style="width:100%" maxlength="256" value="" autocomplete="off" autofocus>
    </div>
    <div style="border:5px">
        <ul id="search-help" style="display:none;
        list-style: none inside;
        border: 1px solid black;">

        </ul>
    </div>

</div>



</div>
