<?php

namespace backend\controllers;

use common\models\Message;
use common\models\Task;
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
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {

        $searchModel = new TicketSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        //$dataProvider->query->andFilterWhere(['user_id' => Yii::$app->user->getId()]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    public function actionView($id)
    {

        $model = $this->findModel($id);
        $messagesDev = Message::find()
            ->where(['ticket_id' => $model->id])
            ->andWhere(['env' => 'dev'])
            ->with('user')
            ->all();
        $messagesUser = Message::find()
            ->where(['ticket_id' => $model->id])
            ->andWhere(['env' => 'user'])
            ->with('user')
            ->all();
        $tasks = Task::find()->where(['ticket_id' => $model->id])->with('user')->all();


        $newMessageDev = new Message();
        if(Yii::$app->request->post('devmsg') && $newMessageDev->load(Yii::$app->request->post()))
        {
            $newMessageDev->ticket_id = $model->id;
            $newMessageDev->env = 'dev';
            if ($newMessageDev->save())
            {
                return $this->refresh();
            }
        }

        $newMessageUser = new Message();

        if(Yii::$app->request->post('usermsg') && $newMessageUser->load(Yii::$app->request->post()))
        {
            $newMessageUser->ticket_id = $model->id;
            $newMessageUser->env = 'user';
            if ($newMessageUser->save())
            {
                return $this->refresh();
            }
        }

        $newTask = new Task();
        if(Yii::$app->request->post('task') && $newTask->load(Yii::$app->request->post()))
        {
            $newTask->ticket_id = $model->id;
            if ($newTask->save())
                return $this->refresh();
        }

        if (Yii::$app->request->isAjax)
        {
            if(Yii::$app->request->post('reason'))
            {
                $model->updateAttributes(['status' => 5]);
                $model->updateAttributes(['deny_reason' => Yii::$app->request->post('reason')]);
                return Yii::$app->request->post('reason');
            }
            if(Yii::$app->request->post('status'))
            {
                $model->updateAttributes(['status' => Yii::$app->request->post('status')]);
                $model->updateAttributes(['deny_reason' => null]);
                return 'noreason';
            }
        }

        return $this->render('view', [
            'model' => $model,
            'messagesDev' => $messagesDev,
            'messagesUser' => $messagesUser,
            'newMessageDev' => $newMessageDev,
            'newMessageUser' => $newMessageUser,
            'tasks' => $tasks,
            'newTask' => $newTask
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