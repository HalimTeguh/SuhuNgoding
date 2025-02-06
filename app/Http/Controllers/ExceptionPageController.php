<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExceptionPageController extends Controller
{
    //
    public function maintenance()
    {
        return view('misc.maintenance');
    }

    public function notFound()
    {
        return view('misc.notFound');
    }
}
