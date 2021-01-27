<?php

namespace app\modules\v1\controllers;

use app\helpers\BehaviorsFromParamsHelper;
use yii\rest\ActiveController;
use yii\filters\ContentNegotiator;
use Yii;

class SessionController extends ActiveController
{
    public $modelClass = 'app\models\Session';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors = BehaviorsFromParamsHelper::behaviors($behaviors);
        $behaviors['contentNegotiator'] = 
                        [
                          'class' => ContentNegotiator::className(),
                          'formats' => [
                              'application/json' => \yii\web\Response::FORMAT_JSON,
                          ],
                        ];
        return $behaviors;   
    }
}