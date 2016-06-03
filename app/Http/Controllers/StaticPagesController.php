<?php
/**
 * Created by PhpStorm.
 * User: sohel
 * Date: 5/4/16
 * Time: 4:40 PM
 */

namespace App\Http\Controllers;


class StaticPagesController extends Controller
{
    public function getTermsOfServices()
    {
        return $this->createView('static.terms_of_services',[]);
    }

    public function getCompensationPage()
    {
        return $this->createView('static.compensation_plans', []);
    }
}