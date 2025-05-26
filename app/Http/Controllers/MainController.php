<?php

namespace App\Http\Controllers;

use App\Http\Requests\Join\JoinRequest;
use App\Mail\ContactMail;
use App\Models\Join;
use App\Services\JoinService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MainController extends Controller
{


    public function __construct(private JoinService $joinService)
    {
    }

    public function index()
    {
        return view('front.index');
    }

    public function join()
    {
        return view('front.join');
    }


    public function terms()
    {
        return view('front.terms');
    }

    public function sendEmail(JoinRequest $request)
    {

        $this->joinService->store($request->validated());

        // $noReplayEmail = "info@motkalem.sa";
        // Mail::to($noReplayEmail)->send(new ContactMail($request->all()));

        return redirect()->route('thankyou')->with(['success' => 'تم الارسال بنجاح']);
    }


    public function aaa()
    {
        return __('fff');
    }

    public function thankyouPage()
    {
        return view('front.thankyou');
    }
}
