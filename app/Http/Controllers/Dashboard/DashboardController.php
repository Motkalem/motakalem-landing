<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Dashboard\AdminBaseController;


class DashboardController extends AdminBaseController
{

    public function index()
    {

            return view('admin.index');
    }


}
