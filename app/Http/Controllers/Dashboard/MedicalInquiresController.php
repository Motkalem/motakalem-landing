<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Center\MedicalInquiry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class MedicalInquiresController extends AdminBaseController
{
    public function index(): Factory|View|Application
    {
        $title = 'استفسارات طبية';

        $search = request()->query('search');

        $query = MedicalInquiry::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('mobile_number', 'LIKE', "%{$search}%");
        }

        $medicalInquiries = $query->orderBy('id', 'desc')
            ->paginate(12);

        return view(
            'admin.medical-inquires.index',
            compact(
                'medicalInquiries',
                'title',
            )
        );
    }

    public function destroy($id): RedirectResponse
    {
        $medicalInquiry = MedicalInquiry::query()->findOrFail($id);
        $medicalInquiry->delete();
        return redirect()->route('dashboard.medical-inquires.index')
            ->with('success', 'تم حذف الاستفسار بنجاح');
    }
}
