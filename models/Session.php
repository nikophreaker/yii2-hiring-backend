<?php

namespace app\models;

use yii\db\ActiveRecord;

class Session extends ActiveRecord
{ 


    public static function tableName()
    {
        return 'session';
    }

    public function rules()
    {
        return [
            [['userID','name','description'], 'required'],
            [['name','description'], 'string'],
            [['start'], 'date', 'format' => 'yyyy-M-d H:m:s'],
            [['duration'], 'integer'],
        ];
    }

    /**
     * Returns a list of behaviors that this component should behave as.
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => '\yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created', 'updated'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated'],
                ],
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }
//------------------------------------------------------------------------------------------------//
// IDENTITY INTERFACE IMPLEMENTATION
//------------------------------------------------------------------------------------------------//

    /**
     * Finds an identity by the given ID.
     *
     * @param  int|string $id The session id.
     * @return IdentityInterface|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['ID' => $id]);
    }
}