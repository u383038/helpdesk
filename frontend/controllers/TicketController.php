<?php

namespace frontend\controllers;

use common\models\Ticket;
use yii\web\Controller;
use Yii;

class TicketController extends Controller
{

    public function actionCreate()
    {
        $model = new Ticket();

        if ($model->load(Yii::$app->request->post())) {
            $model->user_id = Yii::$app->user->getId();
            $model->status = Ticket::STATUS_NEW;
            if ($model->validate()) {
                Yii::$app->session->setFlash('success', 'Ticket created');
                return $this->redirect(['ticket/index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

}