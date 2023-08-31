@extends('layouts.front')

@section('title', 'برنامج متكلم')


@section('menu')
    <li><a href="{{route('home')}}/#homesection">الرئيسيه</a></li>
    <li><a href="{{route('home')}}/#about-us">معلومات عنا</a></li>
    <li><a href="{{route('home')}}/#features">مميزاتنا</a></li>
    <li><a href="{{route('home')}}/#images">صور</a></li>
    {{-- <li><a href="{{route('home')}}/#blog">مصادر مهمه</a></li> --}}
    <li><a href="{{route('home')}}/#questions">الاسئلة المكرره</a></li>
    <li class="active"><a href="{{route('join')}}">الانضمام</a></li>
    <li><a href="{{route('terms_privacy')}}">الشروط والأحكام</a></li>

@endsection


@section('content')

<section class="page-title bg-overlay-black-60 jarallax" data-speed="0.6" data-img-src="{{asset('images/bg/home_slider.webp')}}">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="page-title-name">
            <h1>الانضمام الي برنامج متكلم</h1>
            <p>الانضمام الي البرنامج التدريبي</p>
          </div>
          <ul class="page-breadcrumb">
            <li><a href="{{route('home')}}"><i class="fa fa-home"></i> الرئيسية</a> <i class="fa fa-angle-double-right"></i></li>
            <li><span>الانضمام الي البرنامج التدريبي</span> </li>
          </ul>
        </div>
      </div>
    </div>
  </section>


  <form class="page-section-ptb" method="post" action="{{route('sendEmail')}}">

    @csrf

    <div class="container">

      <div class="row justify-content-center mt-30">
        <div class="col-md-10">
          <div class="section-title text-center">
            <h6>برنامج متكلم</h6>
            <h2 class="title-effect">نموذج تقديم لمعسكر التحكم بالتأتأة</h2>
            <p class="mb-50">يرجي ملئ هذه الحقول</p>
          </div>
        </div>
        <div class="col-lg-8">

          <div class="row mb-3">
            <div class="col-md-6 mb-3">
              <label class="form-label">الاسم *</label>
              <input type="text" placeholder="" class="form-control" name="name" value="{{old('name')}}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">العمر *</label>
              <input type="number" placeholder="" class="form-control" name="age" value="{{old('age')}}" required>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">الهاتف *</label>
              <input type="number" placeholder="" class="form-control" name="phone" value="{{old('phone')}}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">هاتف شخص لحالات الطوارئ *</label>
              <input type="number" placeholder="" class="form-control" name="another_phone" value="{{old('another_phone')}}"
                required>
            </div>

             <div class="col-md-6 mb-3">
              <label class="form-label">البلد *</label>
              <input type="text" placeholder="" class="form-control" name="nationality" value="{{old('nationality')}}" required>
            </div>

            <div class="col-md-6 mb-3">
              <label class="form-label">المدينة *</label>
              <input type="text" placeholder="" class="form-control" name="address" value="{{old('address')}}" required>
            </div>

          </div>



            <!-- <div class="col-md-4 mb-3">
              <label class="form-label">الجنس *</label>
              <select class="wide fancyselect" name="type" value="{{old('type')}}" required>
                <option  selected>ذكر</option>
                <option >انثي</option>
              </select>
            </div> -->
            <!-- <div class="col-md-4 mb-3">
              <label class="form-label">الجنسيه *</label>
              <input type="text" placeholder="" class="form-control" name="nationality" value="{{old('nationality')}}" required>
            </div>
          </div> -->




<!--
          <div class="row mb-3">
            <div class="col-md-6 mb-3">
              <label class="form-label">شدة التأتأة لديك *</label>
              <select class="wide fancyselect" name="severe_stuttering" value="{{old('severe_stuttering')}}" required>
                <option  selected>خفيفة</option>
                <option >متوسطة</option>
                <option >شديدة</option>

              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">تأثير التأتأة بحياتك الاجتماعية *</label>
              <select class="wide fancyselect" name="effect_stuttering_social_life" value="{{old('effect_stuttering_social_life')}}" required>
                <option  selected>خفيفة</option>
                <option >متوسطة</option>
                <option >شديدة</option>
              </select>
            </div>
          </div> -->


          <!-- <div class="row mb-3">
            <div class="col-md-6 mb-3">
              <label class="form-label">تأثير التأتأة في حياتك المهنية / الدراسية *</label>
              <select class="wide fancyselect" name="impact_stuttering_professional_study_life" value="{{old('impact_stuttering_professional_study_life')}}" required>
                <option  selected>خفيفة</option>
                <option >متوسطة</option>
                <option >شديدة</option>

              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">مدى حماسك للتغلب عن التأتأة *</label>
              <select class="wide fancyselect" name="excited_overcome_stuttering" value="{{old('excited_overcome_stuttering')}}" required>
                <option  selected>خفيفة</option>
                <option >متوسطة</option>
                <option >شديدة</option>
              </select>
            </div>
          </div> -->

          <!-- <div class="row mb-3">
            <div class="col-md-12 mb-3">

              <label class="form-label">هل لديك اعاقة جسدية *</label>
              <select class="wide fancyselect" onchange="moHideOrShow(event)" name="have_physical_disability" value="{{old('have_physical_disability')}}" required>
                <option value="yes">نعم</option>
                <option value="no" selected>لا</option>


              </select>
            </div>
            <div class="col-md-12 mb-3" style="display: none;" data-name="have_physical_disability">
              <label class="form-label">وضح نوع الاعاقة</label>
              <textarea class="input-message form-control" placeholder="" rows="7"
                name="type_disability" value="{{old('type_disability')}}"></textarea>
            </div>
          </div> -->

          <!-- <div class="row mb-3">
            <div class="col-md-12 mb-3">
              <label class="form-label">هل لديك مرض عضوي او نفسي *</label>
              <select class="wide fancyselect" onchange="moHideOrShow(event)" name="have_physical_mental_illness" value="{{old('have_physical_mental_illness')}}" required>
                <option value="yes">نعم</option>
                <option value="no" selected>لا</option>


              </select>
            </div>
            <div class="col-md-12 mb-3" style="display: none;" data-name="have_physical_mental_illness">
              <label class="form-label">وضح نوع المرض</label>
              <textarea class="input-message form-control"
                placeholder=" (مثال: مرض في القلب او الرئتین، ثنائي القطبیة،اضطراب فرط الحركة وقصور الانتباه)"
                rows="7" name="type_disease" value="{{old('type_disease')}}"></textarea>
            </div>
          </div> -->



          <!-- <div class="row mb-3">
            <div class="col-md-12 mb-3">
              <label class="form-label">هل يوجد شيء متعلق بصحتك تود اخبارنا به *</label>
              <select class="wide fancyselect" onchange="moHideOrShow(event)" name="anything_related_health" value="{{old('anything_related_health')}}" required>
                <option value="yes">نعم</option>
                <option value="no" selected>لا</option>
              </select>
            </div>
            <div class="col-md-12 mb-3" style="display: none;" data-name="anything_related_health">
              <label class="form-label">اكتب ملاحظاتك</label>
              <textarea class="input-message form-control" placeholder="" rows="7"
                name="notice" value="{{old('notice')}}"></textarea>
            </div>
          </div> -->

          <!-- <div class="row mb-3">
            <div class="col-md-12 mb-3">
              <label class="form-label">هل حصلت علي علاجات او دخلت نوادي او اي شيئ يخص التأتأه سابقا *</label>
              <select class="wide fancyselect" onchange="moHideOrShow(event)" name="treatments_entered_club_anything_related_stuttering_before" value="{{old('treatments_entered_club_anything_related_stuttering_before')}}" required>
                <option value="yes">نعم</option>
                <option value="no" selected>لا</option>
              </select>
            </div>
            <div class="col-md-12 mb-3" style="display: none;" data-name="treatments_entered_club_anything_related_stuttering_before">


              <div class="row mb-3">
                <div class="col-md-12 mb-3">
                  <label class="form-label">اكتب ملاحظاتك والتواريخ</label>
                  <textarea class="input-message form-control" placeholder=" " rows="7"
                    name="write_down_notes_dates" value="{{old('write_down_notes_dates')}}"></textarea>
                </div>

                <div class="col-md-12 mb-3">
                  <div class="row mb-3">
                    <div class="col-md-12 mb-3">
                      <label class="form-label">هل استفدت منها شيئ *</label>
                      <select class="wide fancyselect" onchange="moHideOrShow(event)" name="anything_out_it" value="{{old('anything_out_it')}}" required>
                        <option value="yes" selected>نعم</option>
                        <option value="no">لا</option>
                      </select>
                    </div>
                    <div class="col-md-12 mb-3">
                      <div data-name="anything_out_it">
                        <label class="form-label">اكتب ما استفدته</label>
                        <textarea class="input-message form-control" placeholder="" rows="7"
                        name="write_what_got" value="{{old('write_what_got')}}"></textarea>
                      </div>
                      <div style="display: none;" data-name2="anything_out_it">
                        <label class="form-label">اكتب اسباب عدم الاستفادة</label>
                        <textarea class="input-message form-control" placeholder="" rows="7"
                        name="write_reasons_not_benefiting" style="display: none;" data-name2="anything_out_it" value="{{old('write_reasons_not_benefiting" style="display: none;" data-name2="anything_out_it')}}"></textarea>
                      </div>


                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->

          <!-- <div class="row mb-3">

            <div class="col-md-12 mb-3">
              <label class="form-label">كيف علمت عنـــــــا *</label>
              <select class="wide fancyselect" name="how_find_out_about_us" value="{{old('how_find_out_about_us')}}" required>
                <option value="website">الموقع الالكتروني</option>
                <option value="social">مواقع التواصل </option>
                <option value="man">نصيحة من اشخاص اخرين</option>
                <option value="other">اسباب اخري</option>
              </select>
            </div>
          </div> -->

          <!-- <div class="row mb-3">
            <div class="col-md-12 mb-3">
              <label class="form-label">ما نقاط التحسين او الافكار التي تود تغييرها بالبرامج او النوادي؟</label>
              <textarea class="input-message form-control" placeholder="" rows="7"
                  name="improvement_points_ideas_like_change_programs_clubs" value="{{old('improvement_points_ideas_like_change_programs_clubs')}}"></textarea>
            </div>

          </div> -->

            <div class="row mb-3">
              <div class="col-md-12 mb-3">
                <div class="submit-button text-center">
                  <button id="submit" name="submit" type="submit" value="Send" class="button" value="{{old('submit" type="submit" value="Send" class="button')}}"><span> ارسال</button>
                </div>
              </div>
            </div>

          </div>

        </div>
      </div>
  </form>

@endsection

