<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/10/15
 * Time: 9:24 PM
 */

namespace App\Services;


use Validator;


class EmailChecker
{


    public function isEmailValid($user){
        $inputs = [ 'email' => $user->email];
        $rules = ['email' => 'required|email'];
        $validator = Validator::make($inputs, $rules);
        if($validator->fails()){
            return false;
        }else{
            return true;
        }
    }

}