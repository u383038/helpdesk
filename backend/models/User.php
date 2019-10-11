<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.09.2018
 * Time: 14:01
 */

namespace backend\models;


use yii\db\ActiveRecord;
use backend\models\List1;

class User extends ActiveRecord
{

    public static function tableName()
    {
        return '{{user}}';
    }


}