<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.09.2018
 * Time: 0:18
 */

namespace backend\controllers;


use backend\models\Settings;
use backend\models\User;
use backend\models\List1;
use yii\web\Controller;
use Yii;
use backend\models\SignupForm;
use yii\filters\AccessControl;


class AdminController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['user-index', 'user-create', 'user-update', 'user-delete',],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }


    public function actionUserIndex()
    {
        $model = User::find()->where(['not', ['position' => null]])->all();

        return $this->render('user-index', [
            'model' => $model,
        ]);
    }

    public function actionUserCreate()
    {

        $model = new SignupForm();

        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                $userRole = Yii::$app->authManager->getRole('employee');
                Yii::$app->authManager->assign($userRole, $user->getId());
                return $this->redirect(['admin/user-index']);
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);

    }


    public function actionUserUpdate($id)
    {
        $user = \common\models\User::findOne($id);
        if ($user->id == 1) {
            return 0;
        }

        if (Yii::$app->request->isPost && !Yii::$app->request->Post('newPassword'))
        {
            if($user->load(Yii::$app->request->post()) && $user->save())
            {
                Yii::$app->session->setFlash('success', 'Информация пользователя изменена');
                return $this->refresh();
            }
        }


        if (Yii::$app->request->Post('newPassword'))
        {
            if(Yii::$app->request->Post('newPassword') == Yii::$app->request->Post('repeatNewPassword') )
            {
                $user->setPassword(Yii::$app->request->Post('newPassword'));
                $user->generateAuthKey();
                if($user->save())
                {
                    Yii::$app->session->setFlash('success', 'Пароль пользователя изменен');
                    return $this->refresh();
                }
            }
        }

        return $this->render('user-update', [
            'name' => $user->username,
            'id' => $user->id,
            'model' => $user,
        ]);

    }

    public function actionUserDelete($id)
    {
        $user = User::findOne($id);
        if($user->delete())
        {
            Yii::$app->session->setFlash('success', 'Пользователь удален.');
            return $this->redirect(['admin/user-index']);
        }

    }


}