<?php

namespace app\controllers;

use app\models\Session;
use app\models\User;
use app\models\Status;
use Yii;
use yii\rest\Controller;
use yii\web\ErrorAction;

class SiteController extends Controller
{    
    protected function verbs()
    {
       return [
           'session' => ['GET'],
           'signup' => ['GET'],
           'login' => ['GET'],
           'logout' => ['GET'],
       ];
    }

    public function actionSession()
    {
        $session = Session::find()->select(['ID','userID','name','description'])->all();
            return [
                'status' => Status::STATUS_OK,
                'message' => 'Connected',
                'data' => $session
                ];

    }

    public function actionView($id)
    {
        $session = Session::findOne($id);
        return [
            'status' => Status::STATUS_FOUND,
            'message' => 'Data Found',
            'data' => $session
        ];
    }

    public function actionSignup()
    {
        $model = new User();
        $params = Yii::$app->request->post();
        if(!$params) {
            Yii::$app->response->statusCode = Status::STATUS_BAD_REQUEST;
            return [
                'status' => Status::STATUS_BAD_REQUEST,
                'message' => "Need username, password, and email.",
                'data' => ''
            ];
        }

        $model->name = $params['username'];
        $model->email = $params['email'];

        $model->setPassword($params['password']);
        $model->generateAuthKey();

        if ($model->save()) {
            Yii::$app->response->statusCode = Status::STATUS_CREATED;
            $response['isSuccess'] = 201;
            $response['message'] = 'You are now a member!';
            $response['user'] = \app\models\User::findByUsername($model->name);
            return [
                'status' => Status::STATUS_CREATED,
                'message' => 'You are now a member',
                'data' => User::findByUsername($model->name),
            ];
        } else {
            Yii::$app->response->statusCode = Status::STATUS_BAD_REQUEST;
            $model->getErrors();
            $response['hasErrors'] = $model->hasErrors();
            $response['errors'] = $model->getErrors();
            return [
                'status' => Status::STATUS_BAD_REQUEST,
                'message' => 'Error saving data!',
                'data' => [
                    'hasErrors' => $model->hasErrors(),
                    'getErrors' => $model->getErrors(),
                ]
            ];
        }
    }

    public function actionLogin()
    {
        $params = Yii::$app->request->post();
        if(empty($params['username']) || empty($params['password'])) return [
            'status' => Status::STATUS_BAD_REQUEST,
            'message' => "Need username and password.",
            'data' => ''
        ];

        $user = User::findByUsername($params['username']);
        if(!$user == null){
        if ($user->validatePassword($params['password'])) {
            Yii::$app->response->statusCode = Status::STATUS_FOUND;
            $user->setCookie($user['id']);
            $user->generateAuthKey();
            $user->save();
            // logs in the user
            //Yii::$app->user->login($user);
            return [
                'status' => Status::STATUS_FOUND,
                'message' => 'Login Succeed, save your token',
                'data' => [
                    'name' => $user->name,
                    'token' => $user->auth_key,
                    'email' => $user['email'],
                ]
            ];
        } else {
            Yii::$app->response->statusCode = Status::STATUS_UNAUTHORIZED;
            return [
                'status' => Status::STATUS_UNAUTHORIZED,
                'message' => 'Username and Password not found. Check Again!',
                'data' => ''
            ];
        }
        } else {
            Yii::$app->response->statusCode = Status::STATUS_UNAUTHORIZED;
            return [
                'status' => Status::STATUS_UNAUTHORIZED,
                'message' => 'Username and Password not found. Check Again!',
                'data' => ''
            ];
        }
    }

    public function actionLogout()
    {
        $cookies = Yii::$app->response->cookies;
        // remove a cookie
        $cookies->remove('_i_aksdja');
        $cookies->remove('_aksjdias');
        $cookies->remove('_siamsdilajs');
        // equivalent to the following
        unset($cookies['_aksjdias']);
        unset($cookies['_i_aksdja']);
        unset($cookies['_siamsdilajs']);
        if(!isset($cookies['_aksjdias'])) {
            return [
                'status' => Status::STATUS_OK,
                'message' => "Logout Succesfully.",
                'data' => ''
            ];
        } else {
            return [
                'status' => Status::STATUS_BAD_REQUEST,
                'message' => "Something Wrong",
                'data' => ''
            ];
        }
    }

    public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\ContentNegotiator::className(),
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];

    }
}