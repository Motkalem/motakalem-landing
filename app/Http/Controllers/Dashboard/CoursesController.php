<?php

namespace App\Http\Controllers\Dashboard;

use App\Classes\Helper;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class CoursesController extends AdminBaseController
{
    // Display a listing of the courses
    public function index()
    {
        $title = 'الدورات';
        $courses = Course::orderBy('id','desc')->get();
        return view('admin.courses.index', compact('courses', 'title'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'price' => 'required|numeric|min:0',
            'active' => 'nullable',
        ]);
        $validatedData = array_merge($validatedData, ['active'=> data_get($validatedData,'active') ? 1 : 0]);
        $course = Course::create($validatedData);

        if ( data_get($validatedData,'active')) {
            Course::where('id', '!=', $course->id)
                ->update(['active' => 0]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Course created successfully!',
            'course' => $course
        ]);
    }

    public function edit($id)
    {
        $course = Course::find($id);

        return response()->json($course);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date',
            'price' => 'required|numeric',
        ]);

        $course = Course::find($id);
        $course->update($request->all());
        return response()->json(['success' => 'Course updated successfully.']);
    }

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

