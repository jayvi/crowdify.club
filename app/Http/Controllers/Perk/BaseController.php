<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 8/20/15
 * Time: 4:09 PM
 */

namespace App\Http\Controllers\Perk;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{


    /**
     * sets the first time session values, if these values present in the session that means user didn't complete that step
     *
     * @param Request $request
     */
    protected function setFirstTimeSessionValues(Request $request){
        $request->session()->put('set_account_details', true);
        $request->session()->put('watch_introduction_video', true);
    }
}