<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\ProgramInquiry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProgramInquiresController extends AdminBaseController
{
    public function index(): Factory|View|Application
    {
        $title = 'استفسارات البرامج';

        $search = request()->query('search');

        $query = ProgramInquiry::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('utm_source', 'LIKE', "%{$search}%")
                ->orWhere('utm_medium', 'LIKE', "%{$search}%")
                ->orWhere('mobile_number', 'LIKE', "%{$search}%");
        }

        $programInquiries = $query->orderBy('id', 'desc')
            ->paginate(12);

        return view(
            'admin.program-inquires.index',
            compact(
                'programInquiries',
                'title',
            )
        );
    }

    public function destroy($id): RedirectResponse
    {

        $programInquiry = ProgramInquiry::query()->findOrFail($id);
        $programInquiry->delete();
        return redirect()->route('dashboard.program-inquires.index')
            ->with('success', 'تم حذف الاستفسار بنجاح');
    }
}
