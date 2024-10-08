<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Join;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class JoinsController extends Controller
{
    public function index()
    {
        $title = 'الطلاب';
        // Fetch joined records and order by id with pagination
        $joins = Join::orderBy('id', 'desc')->paginate(12);
        return view('admin.joins.index', compact('joins', 'title'));
    }

    public function create()
    {
        $title = 'إضافة إنضمام جديد';
        return view('admin.joins.create', compact('title'));
    }

    public function store(Request $request)
    {
        // Validate input data including all new fields
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:joins,email',
            'phone' => 'required|string|max:20',
            'another_phone' => 'required|string|max:20',
            'age' => 'required|integer',
            'type' => 'required|in:ذكر,انثي',
            'nationality' => 'nullable|string|max:255',
            'severe_stuttering' => 'nullable|in:متوسطة,خفيفة,شديدة',
            'effect_stuttering_social_life' => 'nullable|in:متوسطة,خفيفة,شديدة',
            'impact_stuttering_professional_study_life' => 'nullable|in:متوسطة,خفيفة,شديدة',
            'excited_overcome_stuttering' => 'nullable|in:متوسطة,خفيفة,شديدة',
            'have_physical_disability' => 'nullable|in:yes,no',
            'type_disability' => 'nullable|string|max:255',
            'have_physical_mental_illness' => 'nullable|in:yes,no',
            'type_disease' => 'nullable|string|max:255',
            'anything_related_health' => 'nullable|in:yes,no',
            'notice' => 'nullable|string|max:255',
            'treatments_entered_club_anything_related_stuttering_before' => 'nullable|in:yes,no',
            'write_down_notes_dates' => 'nullable|string|max:255',
            'anything_out_it' => 'nullable|in:yes,no',
            'write_what_got' => 'nullable|string|max:255',
            'write_reasons_not_benefiting' => 'nullable|string|max:255',
            'how_find_out_about_us' => 'nullable|in:website,social,man,other',
            'improvement_points_ideas_like_change_programs_clubs' => 'nullable|string|max:255',
            'admin_note' => 'nullable|string|max:255',
        ]);

        // Create new join record
        Join::create($request->all());

        return redirect()->route('dashboard.joins.index')->with('success', 'Student created successfully.');
    }

    public function edit($id)
    {
        $title = 'تعديل طالب';

        // Fetch the specific record
        $join = Join::findOrFail($id);
        return view('admin.joins.edit', compact('join', 'title'));
    }

    public function update(Request $request, $id)
    {
        // Validate updated data including all new fields
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email',Rule::unique('joins', 'email')->ignore($id)],
            'phone' => ['required',Rule::unique('joins', 'phone')->ignore($id)],
            'another_phone' => 'required|string|max:20',
            'age' => 'required|integer',
            'type' => 'required|in:ذكر,انثي',
            'nationality' => 'nullable|string|max:255',
            'severe_stuttering' => 'nullable|in:متوسطة,خفيفة,شديدة',
            'effect_stuttering_social_life' => 'nullable|in:متوسطة,خفيفة,شديدة',
            'impact_stuttering_professional_study_life' => 'nullable|in:متوسطة,خفيفة,شديدة',
            'excited_overcome_stuttering' => 'nullable|in:متوسطة,خفيفة,شديدة',
            'have_physical_disability' => 'nullable|in:yes,no',
            'type_disability' => 'nullable|string|max:255',
            'have_physical_mental_illness' => 'nullable|in:yes,no',
            'type_disease' => 'nullable|string|max:255',
            'anything_related_health' => 'nullable|in:yes,no',
            'notice' => 'nullable|string|max:255',
            'treatments_entered_club_anything_related_stuttering_before' => 'nullable|in:yes,no',
            'write_down_notes_dates' => 'nullable|string|max:255',
            'anything_out_it' => 'nullable|in:yes,no',
            'write_what_got' => 'nullable|string|max:255',
            'write_reasons_not_benefiting' => 'nullable|string|max:255',
            'how_find_out_about_us' => 'nullable|in:website,social,man,other',
            'improvement_points_ideas_like_change_programs_clubs' => 'nullable|string|max:255',
            'admin_note' => 'nullable|string|max:255',
        ]);

        // Update the join record
        $join = Join::findOrFail($id);
        $join->update($request->all());

        return redirect()->route('dashboard.joins.index')->with('success', 'Student updated successfully.');
    }

    public function show($id)
    {
        $title = 'عرض طالب';

        // Fetch the specific join record
        $join = Join::findOrFail($id);

        return view('admin.joins.show', compact('join', 'title'));
    }

    public function destroy($id)
    {
        // Find and delete the join record
        $join = Join::findOrFail($id);
        $join->delete();
        return redirect()->route('dashboard.joins.index')->with('success', 'Student deleted successfully.');
    }
}
