<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\ContactUs;
use App\Models\InstallmentPayment;
use App\Models\Student;


class ContactUsMessagesController extends AdminBaseController
{
    public function index()
    {
        $title = 'رسائل إتصل بنا';

        $contactMessages = ContactUs::query()->orderBy('id', 'desc')
            ->paginate(12);

        return view(
         'admin.contactMessages.index',
            compact(
                'contactMessages',
                'title',
            )
        );
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        $contactMessage = ContactUs::query()->findOrFail($id);
        $contactMessage->delete();
        return redirect()->route('dashboard.contact-messages.index')
            ->with('success', 'تم حذف الرسالة');
    }

}
