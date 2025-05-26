<?php

namespace App\Http\Requests\Join;

use Illuminate\Foundation\Http\FormRequest;

class JoinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'], // الاسم *
            'type' => ['in:ذكر,انثي'], // الجنس *
            'nationality' => ['nullable', 'string', 'max:255'], // الجنسيه *
            'age' => ['required', 'int'],  // العمر *
            'address' => ['string', 'max:255'], // >مكان الاقامة *
            // 'postal_code'=>['required','int'], // الرمز البريدي *
            'phone' => ['required', 'numeric'], // الهاتف *
            'email' => ['string', 'email', 'max:255'], // البريد الالكتروني *
            'another_phone' => ['numeric'], // هاتف شخص لحالات الطوارئ *
            'severe_stuttering' => ['in:متوسطة,خفيفة,شديدة'], // شدة التأتأة لديك *
            'effect_stuttering_social_life' => ['in:متوسطة,خفيفة,شديدة'], // تأثير التأتأة بحياتك الاجتماعية *
            'impact_stuttering_professional_study_life' => ['in:متوسطة,خفيفة,شديدة'], //  تأثير التأتأة في حياتك المهنية / الدراسية *
            'excited_overcome_stuttering' => ['in:متوسطة,خفيفة,شديدة'], // مدى حماسك للتغلب عن التأتأة *
            'have_physical_disability' => ['in:yes,no'], // هل لديك اعاقة جسدية *
            'type_disability' => ['nullable', 'string', 'max:255'], // وضح نوع الاعاقة
            'have_physical_mental_illness' => ['in:yes,no'], // هل لديك مرض عضوي او نفسي *
            'type_disease' => ['nullable', 'string', 'max:255'], // وضح نوع المرض
            'anything_related_health' => ['in:yes,no'], //  هل يوجد شيء متعلق بصحتك تود اخبارنا به *
            'notice' => ['nullable', 'string', 'max:255'], // اكتب ملاحظاتك
            'treatments_entered_club_anything_related_stuttering_before' => ['in:yes,no'], // هل حصلت علي علاجات او دخلت نوادي او اي شيئ يخص التأتأه سابقا *
            'write_down_notes_dates' => ['nullable', 'string', 'max:255'], // اكتب ملاحظاتك والتواريخ
            'anything_out_it' => ['in:yes,no'], //هل استفدت منها شيئ *
            'write_what_got' => ['nullable', 'string', 'max:255'], // اكتب ما استفدته
            'write_reasons_not_benefiting' => ['nullable', 'string', 'max:255'], // اكتب اسباب عدم الاستفادة
            'how_find_out_about_us' => ['in:website,social,man,other'], // كيف علمت عنـــــــا *
            'improvement_points_ideas_like_change_programs_clubs' => ['nullable', 'string', 'max:255'], // ما نقاط التحسين او الافكار التي اود تغييرها بالبرامج او النوادي؟
        ];
    }
}
