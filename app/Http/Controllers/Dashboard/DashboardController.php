<?php

namespace App\Http\Controllers\Dashboard;

class DashboardController extends AdminBaseController
{

    public function index()
    {

        $title= 'لوحة التحكم ';
        return view('admin.index',compact('title'));
    }

}
