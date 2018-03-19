<?php
/**
 * Created by PhpStorm.
 * User: alexander
 * Date: 16.03.18
 * Time: 18:30
 */

namespace api\controllers;

use yii\rest\ActiveController;
use common\models\User;
use yii\data\ArrayDataProvider;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';
    /**
     * @SWG\Get(path="/users",
     *     tags={"User"},
     *     summary="Retrieves the collection of User resources.",
     *     operationId="users",
     *     description="array",
     *       produces={"application/json"},
     *     @SWG\Response(
     *         response = 200,
     *         description = "User collection response",

     *     ),
     *     @SWG\Response(
     *         response = 400,
     *         description="Invalid username/password supplied"

     *     ),
     * )
     */

    /**
     * @SWG\Get(path="/user/{id}/",
     *     tags={"User"},
     *     summary="Retrieves the collection of User resources.",
     *     operationId="users",
     *     description="array",
     *       produces={"application/json"},
     *     @SWG\Parameter(
     * 			name="id",
     * 			in="path",
     * 			required=true,
     * 			type="integer",
     * 			description="UUID",
     * 		),
     *     @SWG\Response(
     *         response = 200,
     *         description = "User collection response",

     *     ),

     * )
     */

    public function actionIndexs()
    {
        return User::find();
    }

   /* public function actionVs($id)
    {
        return User::findIdentity($id);
    }*/

}