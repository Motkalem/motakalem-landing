<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Dashboard\AdminBaseController;


class DashboardController extends AdminBaseController
{

    public function index()
    {
        $title= 'لوحة التحكم ';

        return view('admin.index',compact('title'));
    }


}
