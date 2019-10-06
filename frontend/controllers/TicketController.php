<?php

namespace frontend\controllers;

use common\models\Ticket;
use yii\web\Controller;
use Yii;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

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
                $path = 'upload/';
                $model->file->saveAs( $path . $model->file);

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
        $tickets = Ticket::find()->where(['user_id' => Yii::$app->user->getId()])->all();

        return $this->render('index', [
            'tickets' => $tickets,
        ]);
    }

    public function actionView($id)
    {
        $ticket = Ticket::findOne($id);

        return $this->render('view', [
            'ticket' => $ticket,
        ]);
    }

}