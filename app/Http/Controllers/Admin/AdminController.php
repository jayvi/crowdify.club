<?php
/**
 * Created by PhpStorm.
 * User: nathan
 * Date: 10/15/2015
 * Time: 5:27 PM
 */
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use App\User;
class AdminController extends Controller
{
    public function __construct(Guard $auth){
        parent::__construct($auth);
        $this->middleware('auth');

    }

}