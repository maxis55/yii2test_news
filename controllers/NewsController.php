<?php

namespace app\controllers;

use Yii;
use app\models\News;
use app\models\NewsSearch;
use app\models\RssSourceForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;


/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination = [
//        'pageParam' => 'page',
//        'pageSizeParam' => 'rpp',
//        'pageSizeParam' => false,

//        'pageSize' => 10,
        ];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionLive()
    {

        return $this->render('live');
    }


    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionListed()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => News::find()->orderBy('pubdate DESC')->limit(5),
            'pagination' => false
        ]);

        return $this->render('listed', [
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Action for ajax in news/listed.
     * @return string
     */
    public function actionMori()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(isset(Yii::$app->request->get()['keyword'])){
            $keyword=Yii::$app->request->get()['keyword'];
            $arr=News::find()->where(['like', 'title', $keyword])
                ->orderBy('pubdate DESC')
                ->all();
            $res=array();
            foreach ($arr as $item)
                $res[]=['title'=>$item->title,'id'=>$item->id];

            if(empty($arr))
                return ['fail'=>'No matches found'];

            return $res;

        }
        return ['fail'=>'Error. Wrong request parameters.'];
    }

     /**
     * Action for ajax in news/listed.
     * @return string
     */
    public function actionMore()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if(isset(Yii::$app->request->get()['pubdate'])&&isset(Yii::$app->request->get()['amount'])){
            $pubdate=Yii::$app->request->get()['pubdate'];
            $arr=News::find()->where(['<','pubdate',$pubdate])
                ->limit(Yii::$app->request->get()['amount'])
                ->orderBy('pubdate DESC')
                ->all();
            if(empty($arr))
                return ['fail'=>"Nothing left to load."];
            return $arr;
        }else
            return ['fail'=>"Wrong request."];

    }

    /**
     * Get news from site and add them to db.
     * @return mixed
     */
    public function actionRetriever()
    {
        $model = new RssSourceForm();
        if($model->load(Yii::$app->request->post())){
            $feed = $model->link;
            $feed_to_array = simplexml_load_file($feed);
            $s=0;
            $f=0;
            foreach ($feed_to_array->channel->item as $item){
                $filename_from_url = parse_url($item->enclosure['url']);
                $ext = pathinfo($filename_from_url['path'], PATHINFO_EXTENSION);
                $fileName = "image-prefix-" . time() . "." . $ext;
                $tDate=strtotime(strval($item->pubDate));
                if(file_exists('dbimages/'.$fileName))
                    $fileName=str_replace('prefix-','prefix-0',$fileName);
                $model2=new News();
                $model2->title=strval($item->title);
                $model2->description=strval($item->description);
                $model2->pubdate=date('Y-m-d H:m:s',$tDate);
                $model2->img_path=$fileName;
                $model2->link=strval($item->link);

                if($model2->save()){
                    copy($item->enclosure['url'], 'dbimages/'.$fileName);
                    $s++;
                }
                else{
                    $f++;
                }
            }
            Yii::$app->session->setFlash('rssDataRetrieved',"Added fields:$s Failed to add(already exist):$f");
        }

        return $this->render('retrieve',[
            'model' => $model,
        ]);
    }
    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model=$this->findModel($id);
        if(file_exists('dbimages/'.$model->img_path))
            unlink('dbimages/'.$model->img_path);
        $model->delete();
        return $this->redirect(['index']);
    }


    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
