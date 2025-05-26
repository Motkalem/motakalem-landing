@php use Carbon\Carbon; @endphp

    <!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>

        عقد الانضمام لبرنامج متكلم للتحكم بالتأتأه
    </title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">

    <style>
        @font-face {
            font-family: 'SarRegular';
            src: url('/fonts/font/sar-Regular.otf') format('opentype');
        }

        .riyal-symbol {
            width: 1.6rem;
            height: 2rem;
            display: inline-flex;
            justify-content: center;
            align-items: center;

            font-family: 'SarRegular', sans-serif !important;
            font-size: 1.3rem;
            font-weight: 100 !important;
        }
    </style>
</head>
<body style="margin:0px;padding:0px;min-width:100%;background-color:rgb(243,242,240)">
<center style="width:100%;table-layout:fixed;background-color:rgb(243,242,240)">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:rgb(243,242,240)"
           bgcolor="#f3f2f0">
        <tbody>
        <tr>
            <td width="100%" style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                <div style="max-width:70%;margin:0px auto;font-family:Cairo,Geneva,sans-serif">
                    <table align="center" cellpadding="0" cellspacing="0" border="0"
                           style="border-spacing:0px;margin:0px auto;width:100%;font-family:Cairo,Geneva,sans-serif">
                        <tbody>
                        <tr>
                            <td style="padding:0px;font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                                <table border="0" width="100%" cellpadding="0" cellspacing="0"
                                       style="font-family:Cairo,Geneva,sans-serif">
                                    <tbody>
                                    <tr>
                                        <td style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                                            <table style="width:100%;font-family:Cairo,Geneva,sans-serif"
                                                   cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                <tr>
                                                    <td align="center"
                                                        style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                                                        <center style="font-family:Cairo,Geneva,sans-serif">
                                                            <table border="0" align="center" width="100%"
                                                                   cellpadding="0" cellspacing="0"
                                                                   style="margin:0px auto;font-family:Cairo,Geneva,sans-serif">
                                                                <tbody>
                                                                <tr>
                                                                    <td style="padding:0px;font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)"
                                                                        bgcolor="#FFFFFF">
                                                                        <table cellpadding="0" cellspacing="0"
                                                                               border="0" width="100%" bgcolor="#f3f2f0"
                                                                               style="font-family:Cairo,Geneva,sans-serif">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td style="padding:0px;text-align:left;font-size:0px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">

                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                                                                                    &nbsp;
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </center>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                       style="border-spacing:0px;font-family:Cairo,Geneva,sans-serif;padding-left:10px"
                                       bgcolor="#FFFFFF">
                                    <tbody>
                                    <tr>
                                        <td colspan="4" align="right"
                                            style="padding:20px;font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                                            <p align="center"
                                               style="font-weight:bold;font-size:20px;text-align:center;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                                                عقد الانضمام لبرنامج متكلم للتحكم بالتأتأه
                                            </p>
                                            <hr/>
                                            @php
                                                $createdAt = \Carbon\Carbon::parse(data_get($data, 'created_at'));
                                            @endphp

                                            <p style="direction:rtl;line-height:30px;font-size:16px;text-align:right;font-family:Cairo,Geneva,sans-serif">
                                                ‎انه في يوم {{ $createdAt->translatedFormat('l') }}
                                                الموافق {{ $createdAt->format('Y/n/j') }}
                                                م .
                                                <br/>
                                                ‎تحرر هذا العقد بين كلاً من:
                                                <br/>
                                                ‎أولاً: شركة متكلم الطبية، وعنوانها: جدة، حي الورود. سجل تجاري رقم:
                                                4030511477
                                                <br/>
                                                ‎البريد الألكتروني: info@motkalem.sa
                                            </p>

                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="center"
                                            style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right"
                                            style="direction:rtl;font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                                            <p style="font-family:Cairo,Geneva,sans-serif; padding-right:10px">
                                                يقر الطرفان بأهليتهما للتعاقد وقد اتفقا على ما يلي:
                                                <br/>
                                            </p>
                                            <ul style="font-family:Cairo,Geneva,sans-serif">
                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    الطرف الأول: شركة متكلم الطبية، وعنوانها: جدة، حي الورود. يشار اليها
                                                    لاحقا ب "الشركة" (برقم سجل تجاري 4030511477).
                                                </li>
                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    الطرف الثاني: {{data_get($data, 'name')}}, رقم
                                                    الهوية: {{data_get($data, 'id_number')}},  . (يشار إليه لاحقاً بـ
                                                    رقم جوال: {{ data_get($data, 'phone') }}.
                                                    "العميل").
                                                </li>
                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    مقدمة:
                                                </li>
                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    نظرًا لرغبة الطرف الثاني في الاشتراك ببرنامج "متكلم للتحكم بالتأتأة"
                                                    المقدم من الطرف الأول، عليه يقر ويوافق ويلتزم الطرف الثاني <br/>على
                                                    جميع الاحكام والشروط والبنود الموضحة أدناه في العقد، كما يعتبر هذا
                                                    العقد موقع بشكل رقمي ورسمي من قبل الطرف الثاني:
                                                </li>
                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    البند الأول: تفاصيل البرنامج:
                                                    <br/>
                                                    1. اسم البرنامج: برنامج متكلم للتحكم بالتأتأة.
                                                    <br/>
                                                    2. مدة البرنامج: أربعة أشهر.
                                                    <br/>
                                                    3. محتوى البرنامج: تدريب على التحكم بالتأتأة.
                                                    <br/>
                                                    4. تاريخ بدء
                                                    البرنامج الحضوري: {{ Carbon::parse($data->package?->starts_date)?->format('Y/n/j')  }}
                                                    .

                                                </li>
                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    @if($data->package?->total > 0 || $data->package?->first_inst > 0 )
                                                        البند الثاني: التكلفة وجدول السداد:
                                                        <br/>

                                                        1. التكلفة الإجمالية للبرنامج
                                                        @if($data->package?->payment_type === \App\Models\Package::ONE_TIME)
                                                            {!! '<span style="font-weight: bold;">' . $data->package?->total . '</span>' !!}
                                                        @else
                                                            {!! '<span style="font-weight: bold;">'
                                                . ( $data->package?->first_inst + $data->package?->second_inst
                                                    + $data->package?->third_inst +  $data->package?->fourth_inst +  $data->package?->fifth_inst) . '</span>' !!}
                                                        @endif
                                                        <img style="width:12px" src="{{public_path('/images/riyal-sym.svg.png')}}" />.
                                                    @endif

                                                    @if($data->package?->payment_type === 'installments')
                                                        <br/>
                                                        2. جدول السداد: يتم دفع الأقساط على النحو التالي:
                                                        <br/>
                                                        @php
                                                            $installments = [
                                                                'الأول' => $data->package?->first_inst,
                                                                'الثاني' => $data->package?->second_inst,
                                                                'الثالث' => $data->package?->third_inst,
                                                                'الرابع' => $data->package?->fourth_inst,
                                                                'الخامس' => $data->package?->fifth_inst,
                                                            ];
                                                        @endphp
                                                        @foreach ($installments as $key => $value)
                                                            @if ($value > 0)
                                                                ◦ القسط {{ $key }}: {{ $value }}

                                                                <img style="width:12px" src="{{public_path('/images/riyal-sym.svg.png')}}" /> .
                                                                @if ($loop->first)
                                                                    يُدفع عند الاشتراك.
                                                                @else
                                                                    يُدفع قبل بدء المرحلة {{ $key }}.
                                                                @endif

                                                                <br/>
                                                            @endif
                                                        @endforeach
                                                    @endif

                                                </li>
                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    البند الثالث: الالتزامات المتبادلة:
                                                    <br/>
                                                    1. يلتزم الطرف الأول بتقديم كافة التدريب والمحتوى اللازم للطرف
                                                    الثاني خلال مدة البرنامج.
                                                    <br/>
                                                    2. يلتزم الطرف الثاني بدفع جميع الأقساط في مواعيدها المحددة.
                                                    <br/>
                                                    3. يلتزم الطرف الثاني بالالتزام بجميع تعليمات المدربين والمشاركة
                                                    الفعالة في البرنامج.
                                                    <br/>
                                                    4. في حال عدم الالتزام بدفع الأقساط في المواعيد المحددة، يحق للطرف
                                                    الأول إنهاء الاشتراك واسترداد المبلغ المدفوع.
                                                </li>


                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    ‎البند الرابع: طرق السداد:<br/>
                                                    ‎9. تتم طرق السداد عبر القنوات البنكية الرسمية للشركة فقط.

                                                </li>

                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">

                                                    ‎البند الخامس: سياسة استرداد الأموال:<br/>
                                                    ‎ 10. يحق للطرف الثاني إلغاء الاشتراك واسترجاع الأموال في مدة لا
                                                    تزيدعن 3 أيام من تاريخ بداية الاشتراك ويبدا الاشتراك بعد سداد
                                                    الدفعةالاولى<br/>
                                                    ‎ 11. يعود سبب المذكور في البند الخامس ، الفقره رقم ١  ذلك إلى
                                                    ارتباطالاشتراك بحجوزات فندقية ومقاعد تدريبيه ومواعيد و جلسات
                                                    وتكاليفيتم دفعها مقابل جدولة الشخص في الخطة التدريبية.<br/>
                                                </li>

                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    البند(المسؤولية العقدية للطرف الأول ):<br/>
                                                    ‎12. لايضمن الطرف الأول الإلتزام بالنتيجة وإنما يلتزم ببذل العناية
                                                    اللازمة في التطوير حيث أن البرنامج مقدم للتطوير،وتزيد أو تنخفض
                                                    النتائج حسب التزام الطرف الثاني.<br/>

                                                    ‎ البند (الإخطارات)
                                                    ‎يقر كلا المتعاقدين أنه اتخذ موطنًا مختارًا له عنوانه الموضح قرين
                                                    اسم كلاً منهما بصدر هذا العقد وأن كافة المراسلات والإعلانات
                                                    والإخطارات المرسلة على هذا العنوان تعتبر صحيحة ومنتجة لكافة آثارها
                                                    القانونية ما لم يخطر أحد الطرفين الآخر كتابة بتغيير عنوانه.

                                                </li>

                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    ‎ البند (طبيعة العلاقة القانونية بين الطرفين)<br/>
                                                    ‎أقر الطرفان بأن العلاقة القانونية التي تنشأ بينهم لا تعد علاقة عمل
                                                    ولا أن هذا العقد عقد عمل،ك، وأن الطرف الأول يسخر أداة التطوير
                                                    المملوكة له لتنفيذ الخدمة التطويريه فقط دون أن يكون له دورًا في
                                                    تحقيق النتيجة، وبالتالي لا ينطبق على هذه العلاقة القانونية أي من
                                                    أحكام قوانين العمل أو أحكام الكفالة أو الضمان.
                                                    ‎يُسأل الطرف الثاني مسئولية شخصية في حالة:
                                                    <br/>* إخلاله ببنود هذا العقد.
                                                    <br/> * انتهاك أي حق من حقوق العملاء أو الغير.
                                                    <br/>* الإخلال بالنصوص القانونية المعمول بها.

                                                </li>
                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    البند (عدم الحصرية)
                                                    ‎اتفق الطرفان على أن العلاقة بينهم ليست علاقة حصرية، فيسمح للطرف
                                                    الاول بتعدد مقدمي الخدمات (التطوير/الدورات التدريبية)، وبالتالي من
                                                    حق الطرف الأول التعاقد مع الغير لتقديم الخدمات في العقد وتقديم خدمات
                                                    مماثلة للطرف الثاني، ولا يحق للطرف الثاني الاعتراض على ذلك في أي وقت
                                                    من الأوقات.

                                                </li>

                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    ‎البند الثامن: حل النزاعات:
                                                    ‎14. في حال حدوث أي نزاع بين الطرفين حول هذا العقد، يتم حله وديًا.
                                                    وفي حال عدم التوصل لحل ودي، يتم اللجوء إلى التحكيم أو المحاكمالمختصة
                                                    في مدينة جدة.

                                                </li>
                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">

                                                    ‎البند (تعديل العقد ونُسخ العقد)
                                                    ‎أي تعديلات في بنود هذا العقد يجب أن تكون مكتوبة وموقع عليها من طرفي
                                                    العقد.
                                                    ‎تحرر هذا العقد من نسختين متطابقتين لكل نسخة ذات القوام والأثر
                                                    القانوني، وكل نسخة موقع عليها من الطرفين، وقد تسلم كل طرف نسخة للعمل
                                                    بموجبها.
                                                    ‎ويحق للطرف الأول تعديل بنود شروط وأحكام هذا العقد وسياسة الخصوصية
                                                    في أي وقت من الأوقات، وتسري هذه التعديلات في مواجهة الطرف الثاني
                                                    وكافة مستخدمي الموقع من تاريخ نشرها عبر الموقع.

                                                </li>

                                                <li style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding-bottom:10px">
                                                    يعتبر قبولك للشروط والاحكام توقيعا الكترونيا ورقميا على العقد .

                                                </li>

                                            </ul>
                                            <br/>
                                            <p style="font-family:Cairo,Geneva,sans-serif; padding-right:20px; padding-left:20px">
                                                توقيع الطرف الأول: شركة متكلم الطبية، وعنوانها: جدة، حي الورود.
                                                <br/>
                                                توقيع الطرف الثاني: {{data_get($data, 'name')}}
                                            </p>
                                            <br/>
                                            <p style="font-family:Cairo,Geneva,sans-serif;padding-right:20px; padding-left:20px"
                                               ; padding-right:20px>
                                                للمزيد من المعلومات، يمكنكم زيارة موقعنا على الرابط التالي:
                                                <br/>
                                                <br/>
                                                <a href="https://motkalem.sa/"
                                                   style="font-family:Cairo,Geneva,sans-serif">https://motkalem.sa/</a>
                                            </p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td align="center"
                                style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding:30px">
                                <table width="80%" border="0" cellpadding="0" cellspacing="0" align="center"
                                       style="font-family:Cairo,Geneva,sans-serif">
                                    <tbody>
                                    <tr>
                                        <td style="border-bottom:1px solid rgb(212,208,202);padding:10px 0px;font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding:20px 0px;text-align:center">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                   align="center" style="font-family:Cairo,Geneva,sans-serif">
                                                <tbody>
                                                <tr>
                                                    <td align="center"
                                                        style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                                                        <a href="https://motkalem.sa/"
                                                           style="color:rgb(38,38,38);font-family:Cairo,Geneva,sans-serif;text-decoration:none;font-weight:bold"
                                                           target="_blank">motkalem.sa</a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <br/>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                   align="center" style="font-family:Cairo,Geneva,sans-serif">
                                                <tbody>
                                                <tr>
                                                    <td align="center"
                                                        style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                                                        <a href="mailto:info@motkalem.sa"
                                                           style="color:rgb(38,38,38);font-family:Cairo,Geneva,sans-serif;text-decoration:none"
                                                           target="_blank">info@motkalem.sa</a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <br/>
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%"
                                                   align="center" style="font-family:Cairo,Geneva,sans-serif">
                                                <tbody>
                                                <tr>
                                                    <td align="center"
                                                        style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38)">
                                                        <a href="tel:+966-12-3456-789"
                                                           style="color:rgb(38,38,38);font-family:Cairo,Geneva,sans-serif;text-decoration:none"
                                                           target="_blank">+966-12-3456-789</a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <br/>
                                <br/>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size:16px;font-family:Cairo,Geneva,sans-serif;color:rgb(38,38,38);padding:0px">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</center>
</body>
</html>
