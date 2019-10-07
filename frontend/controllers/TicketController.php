<?php

namespace frontend\controllers;

use common\models\Ticket;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\models\TicketSearch;

class TicketController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCreate()
    {
        $model = new Ticket();

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = Yii::$app->user->getId();
            $model->status = Ticket::STATUS_NEW;
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->validate() && $model->save()) {
                if($model->file)
                {
                    $path = 'upload/';
                    $model->file->saveAs( $path . $model->file);
                }

                Yii::$app->session->setFlash('success', 'Ticket created');
                return $this->redirect(['ticket/index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionIndex()
    {

        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['user_id' => Yii::$app->user->getId()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'user_id' => $model->user_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Ticket::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}