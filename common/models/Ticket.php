<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property int $user_id
 * @property int $status
 *
 * @property Message[] $messages
 * @property Task[] $tasks
 * @property User $user
 */
class Ticket extends \yii\db\ActiveRecord
{

    const STATUS_NEW = 0;
    const STATUS_CONSIDERATION = 1;
    const STATUS_PROCESS = 2;
    const STATUS_SUCCESS = 3;
    const STATUS_CANCELED = 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['id', 'user_id', 'status'], 'integer'],
            [['name', 'type'], 'string', 'max' => 255],
            [['description'], 'string', 'max' => 511],
            [['id', 'user_id'], 'unique', 'targetAttribute' => ['id', 'user_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['file'], 'file']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'type' => 'Type',
            'user_id' => 'User ID',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(Message::className(), ['ticket_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['ticket_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
