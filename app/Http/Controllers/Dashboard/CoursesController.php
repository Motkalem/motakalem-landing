<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Helper;
use App\Models\Course;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class CoursesController extends AdminBaseController
{
    // Display a listing of the courses
    public function index(): Factory|View|Application
    {
        $title = 'الدورات';
        $courses = Course::orderBy('active','desc')->get();
        return view('admin.courses.index', compact('courses', 'title'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create(): Factory|View|Application
    {
        $title = 'إنشاء الدورة';
        return view('admin.courses.createEdit', compact('title'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'active' => 'nullable',
        ]);
        $validatedData = array_merge($validatedData, ['active'=> data_get($validatedData,'active') ? 1 : 0]);
        $course = Course::create($validatedData);

        if ( data_get($validatedData,'active')) {
            Course::where('id', '!=', $course->id)
                ->update(['active' => 0]);
        }

        return to_route('dashboard.courses.index')->with(['success' => 'Course updated successfully.']);
    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function edit($id): Factory|View|Application
    {
        $course = Course::query()->findOrFail($id);

       return view('admin.courses.createEdit', compact('course'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date',
        ]);

        $course = Course::find($id);
        $course->update(array_merge($request->all(), ['active'=> $request->active ? 1 : 0]));

         Course::query()->where('id', '!=',$id)->update(['active'=> 0]);

         return to_route('dashboard.courses.index')->with(['success' => 'Course updated successfully.']);

    }

    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function show($id)
    {
        $title = 'العقود';

        $course = Course::with('contracts')->find($id);
        return view('admin.courses.show', compact('course', 'title'));
    }



    /**
     * Remove the specified package from storage.
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        $course = Course::with('contracts')->findOrFail($id);

        Helper::tryDelete($course);
        if (!Course::where('active', 1)->exists()) {

            Course::latest()->first()?->update(['active' => 1]);
        }
        return redirect()->route('dashboard.courses.index');
    }
}

