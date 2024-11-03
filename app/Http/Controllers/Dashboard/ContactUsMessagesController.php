<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ContactUs;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;


class ContactUsMessagesController extends AdminBaseController
{
    public function index(): Factory|View|Application
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

    public function destroy($id): RedirectResponse
    {
        $contactMessage = ContactUs::query()->findOrFail($id);
        $contactMessage->delete();
        return redirect()->route('dashboard.contact-messages.index')
            ->with('success', 'تم حذف الرسالة');
    }

}
