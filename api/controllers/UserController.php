<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 16.03.18
 * Time: 18:30
 */

namespace api\controllers;

use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'api\models\User';


}