<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * @return object auth api
     */
    public function guard() 
    {
        return Auth::guard('api');
    }

}
