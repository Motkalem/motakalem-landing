<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Models\Join;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MainController extends Controller
{
    public function index(){
        return view('front.index');
    }

    public function join(){
        return view('front.join');
    }


    public function terms(){
        return view('front.terms');
    }

    public function sendEmail(Request $request){
        $request->validate([
            'name'=>['required', 'string', 'max:255'], // الاسم *
            'type'=>['in:ذكر,انثي'], // الجنس *
            'nationality'=>['required', 'string', 'max:255'], // الجنسيه *
            'age'=>['required','int'],  // العمر *
            'address'=>['string', 'max:255'], // >مكان الاقامة *
            // 'postal_code'=>['required','int'], // الرمز البريدي *
            'phone'=>['required','numeric'], // الهاتف *
            'email'=> ['string', 'email', 'max:255'], // البريد الالكتروني *
            'another_phone'=>['numeric'], // هاتف شخص لحالات الطوارئ *
            'severe_stuttering'=> ['in:متوسطة,خفيفة,شديدة'], // شدة التأتأة لديك *
            'effect_stuttering_social_life'=>['in:متوسطة,خفيفة,شديدة'], // تأثير التأتأة بحياتك الاجتماعية *
            'impact_stuttering_professional_study_life'=>['in:متوسطة,خفيفة,شديدة'], //  تأثير التأتأة في حياتك المهنية / الدراسية *
            'excited_overcome_stuttering'=>['in:متوسطة,خفيفة,شديدة'], // مدى حماسك للتغلب عن التأتأة *
            'have_physical_disability'=>['in:yes,no'], // هل لديك اعاقة جسدية *
            'type_disability'=>['nullable','string', 'max:255'], // وضح نوع الاعاقة
            'have_physical_mental_illness'=>['in:yes,no'], // هل لديك مرض عضوي او نفسي *
            'type_disease'=>['nullable','string', 'max:255'], // وضح نوع المرض
            'anything_related_health'=>['in:yes,no'], //  هل يوجد شيء متعلق بصحتك تود اخبارنا به *
            'notice'=>['nullable','string', 'max:255'], // اكتب ملاحظاتك
            'treatments_entered_club_anything_related_stuttering_before'=>['in:yes,no'], // هل حصلت علي علاجات او دخلت نوادي او اي شيئ يخص التأتأه سابقا *
            'write_down_notes_dates'=>['nullable','string', 'max:255'], // اكتب ملاحظاتك والتواريخ
            'anything_out_it'=>['in:yes,no'], //هل استفدت منها شيئ *
            'write_what_got'=>['nullable','string', 'max:255'], // اكتب ما استفدته
            'write_reasons_not_benefiting'=>['nullable','string', 'max:255'], // اكتب اسباب عدم الاستفادة
            'how_find_out_about_us'=>['in:website,social,man,other'], // كيف علمت عنـــــــا *
            'improvement_points_ideas_like_change_programs_clubs'=>['nullable','string', 'max:255'], // ما نقاط التحسين او الافكار التي اود تغييرها بالبرامج او النوادي؟
        ]);


        Join::create([
            'name'=>$request->name,
            'type'=>$request->type ?? 'ذكر',
            'nationality'=>$request->nationality,
            'age'=>$request->age,
            'address'=>$request->address,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'another_phone'=>$request->another_phone,
            'severe_stuttering'=>$request->severe_stuttering,
            'effect_stuttering_social_life'=>$request->effect_stuttering_social_life,
            'impact_stuttering_professional_study_life'=>$request->impact_stuttering_professional_study_life,
            'excited_overcome_stuttering'=>$request->excited_overcome_stuttering,
            'have_physical_disability'=>$request->have_physical_disability,
            'type_disability'=>$request->type_disability,
            'have_physical_mental_illness'=>$request->have_physical_mental_illness,
            'type_disease'=>$request->type_disease,
            'anything_related_health'=>$request->anything_related_health,
            'notice'=>$request->notice,
            'treatments_entered_club_anything_related_stuttering_before'=>$request->treatments_entered_club_anything_related_stuttering_before,
            'write_down_notes_dates'=>$request->write_down_notes_dates,
            'anything_out_it'=>$request->anything_out_it,
            'write_what_got'=>$request->write_what_got,
            'write_reasons_not_benefiting'=>$request->write_reasons_not_benefiting,
            'how_find_out_about_us'=>$request->how_find_out_about_us,
            'improvement_points_ideas_like_change_programs_clubs'=>$request->improvement_points_ideas_like_change_programs_clubs,
        ]);


        // $noReplayEmail = "info@motkalem.com";
        // Mail::to($noReplayEmail)->send(new ContactMail($request->all()));

        return redirect()->route('thankyou')->with(['success' => 'تم الارسال بنجاح']);


    }


    public function aaa(){
        return __('fff');
    }

    public function thankyouPage(){
        return view('front.thankyou');
    }
}
