<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'يجب قبول حقل :attribute.',
    'accepted_if' => 'يجب قبول حقل :attribute عندما يكون :other هو :value.',
    'active_url' => 'حقل :attribute يجب أن يكون رابط صالح.',
    'after' => 'حقل :attribute يجب أن يكون تاريخًا بعد :date.',
    'after_or_equal' => 'حقل :attribute يجب أن يكون تاريخًا بعد أو يساوي :date.',
    'alpha' => 'حقل :attribute يجب أن يحتوي فقط على أحرف.',
    'alpha_dash' => 'حقل :attribute يجب أن يحتوي فقط على أحرف وأرقام وشرطات وشرطات سفلية.',
    'alpha_num' => 'حقل :attribute يجب أن يحتوي فقط على أحرف وأرقام.',
    'array' => 'حقل :attribute يجب أن يكون مصفوفة.',
    'ascii' => 'حقل :attribute يجب أن يحتوي فقط على أحرف أبجدية وأرقام بترميز ASCII.',
    'before' => 'حقل :attribute يجب أن يكون تاريخًا قبل :date.',
    'before_or_equal' => 'حقل :attribute يجب أن يكون تاريخًا قبل أو يساوي :date.',
    'between' => [
        'array' => 'حقل :attribute يجب أن يحتوي على عدد بين :min و :max عنصر.',
        'file' => 'حقل :attribute يجب أن يكون بين :min و :max كيلوبايت.',
        'numeric' => 'حقل :attribute يجب أن يكون بين :min و :max.',
        'string' => 'حقل :attribute يجب أن يكون بين :min و :max حرفًا.',
    ],
    'boolean' => 'حقل :attribute يجب أن يكون إما true أو false.',
    'can' => 'حقل :attribute يحتوي على قيمة غير مصرح بها.',
    'confirmed' => 'تأكيد حقل :attribute غير مطابق.',
    'current_password' => 'كلمة المرور غير صحيحة.',
    'date' => 'حقل :attribute يجب أن يكون تاريخًا صحيحًا.',
    'date_equals' => 'حقل :attribute يجب أن يكون تاريخًا يساوي :date.',
    'date_format' => 'حقل :attribute لا يتوافق مع الصيغة :format.',
    'decimal' => 'حقل :attribute يجب أن يحتوي على :decimal أماكن عشرية.',
    'declined' => 'يجب رفض حقل :attribute.',
    'declined_if' => 'يجب رفض حقل :attribute عندما يكون :other هو :value.',
    'different' => 'حقل :attribute يجب أن يكون مختلفًا عن :other.',
    'digits' => 'حقل :attribute يجب أن يكون :digits أرقام.',
    'digits_between' => 'حقل :attribute يجب أن يكون بين :min و :max أرقام.',
    'dimensions' => 'حقل :attribute يحتوي على أبعاد صورة غير صالحة.',
    'distinct' => 'حقل :attribute يحتوي على قيمة مكررة.',
    'doesnt_end_with' => 'حقل :attribute يجب أن لا ينتهي بأحد القيم التالية: :values.',
    'doesnt_start_with' => 'حقل :attribute يجب أن لا يبدأ بأحد القيم التالية: :values.',
    'email' => 'حقل :attribute يجب أن يكون بريدًا إلكترونيًا صالحًا.',
    'ends_with' => 'حقل :attribute يجب أن ينتهي بأحد القيم التالية: :values.',
    'enum' => 'القيمة المحددة :attribute غير صالحة.',
    'exists' => 'القيمة المحددة :attribute غير صالحة.',
    'file' => 'حقل :attribute يجب أن يكون ملفًا.',
    'filled' => 'حقل :attribute يجب أن يحتوي على قيمة.',
    'gt' => [
        'array' => 'حقل :attribute يجب أن يحتوي على أكثر من :value عنصر.',
        'file' => 'حقل :attribute يجب أن يكون أكبر من :value كيلوبايت.',
        'numeric' => 'حقل :attribute يجب أن يكون أكبر من :value.',
        'string' => 'حقل :attribute يجب أن يكون أكبر من :value حرفًا.',
    ],

    'image' => 'يجب أن يكون حقل :attribute صورة.',
'in' => 'القيمة المحددة لـ :attribute غير صالحة.',
'in_array' => 'حقل :attribute غير موجود في :other.',
'integer' => 'يجب أن يكون حقل :attribute عددًا صحيحًا.',
'ip' => 'يجب أن يكون حقل :attribute عنوان IP صالحًا.',
'ipv4' => 'يجب أن يكون حقل :attribute عنوان IPv4 صالحًا.',
'ipv6' => 'يجب أن يكون حقل :attribute عنوان IPv6 صالحًا.',
'json' => 'يجب أن يكون حقل :attribute سلسلة JSON صالحة.',
'lowercase' => 'يجب أن يكون حقل :attribute حروفًا صغيرة.',
'lt' => [
    'array' => 'يجب أن يحتوي حقل :attribute على أقل من :value عنصرًا.',
    'file' => 'يجب أن يكون حجم حقل :attribute أقل من :value كيلوبايت.',
    'numeric' => 'يجب أن يكون حقل :attribute أقل من :value.',
    'string' => 'يجب أن يكون حقل :attribute أقل من :value حرفًا.',
],
'lte' => [
    'array' => 'يجب أن لا يحتوي حقل :attribute على أكثر من :value عنصرًا.',
    'file' => 'يجب أن يكون حجم حقل :attribute أقل من أو يساوي :value كيلوبايت.',
    'numeric' => 'يجب أن يكون حقل :attribute أقل من أو يساوي :value.',
    'string' => 'يجب أن يكون حقل :attribute أقل من أو يساوي :value حرفًا.',
],
'mac_address' => 'يجب أن يكون عنوان MAC صالحًا.',
'max' => [
    'array' => 'يجب ألا يحتوي حقل :attribute على أكثر من :max عنصرًا.',
    'file' => 'يجب ألا يتجاوز حجم حقل :attribute :max كيلوبايت.',
    'numeric' => 'يجب ألا يتجاوز حقل :attribute :max.',
    'string' => 'يجب ألا يتجاوز حقل :attribute :max حرفًا.',
],
'max_digits' => 'يجب ألا يحتوي حقل :attribute على أكثر من :max رقمًا.',
'mimes' => 'يجب أن يكون حقل :attribute ملفًا من نوع: :values.',
'mimetypes' => 'يجب أن يكون حقل :attribute ملفًا من نوع: :values.',
'min' => [
    'array' => 'يجب أن يحتوي حقل :attribute على :min عنصرًا على الأقل.',
    'file' => 'يجب أن يكون حجم حقل :attribute :min كيلوبايت على الأقل.',
    'numeric' => 'يجب أن يكون حقل :attribute :min على الأقل.',
    'string' => 'يجب أن يكون حقل :attribute :min حرفًا على الأقل.',
],
'min_digits' => 'يجب أن يحتوي حقل :attribute على :min رقمًا على الأقل.',
'missing' => 'يجب أن يكون حقل :attribute مفقودًا.',
'missing_if' => 'يجب أن يكون حقل :attribute مفقودًا عند :other يساوي :value.',
'missing_unless' => 'يجب أن يكون حقل :attribute مفقودًا ما لم يكن :other يساوي :value.',
'missing_with' => 'يجب أن يكون حقل :attribute مفقودًا عند وجود :values.',
'missing_with_all' => 'يجب أن يكون حقل :attribute مفقودًا عند وجود :values.',
'multiple_of' => 'يجب أن يكون حقل :attribute مضاعفًا لـ :value.',
'not_in' => 'القيمة المحددة لـ :attribute غير صالحة.',
'not_regex' => 'تنسيق حقل :attribute غير صالح.',
'numeric' => 'يجب أن يكون حقل :attribute رقمًا.',
'password' => [
    'letters' => 'يجب أن يحتوي حقل :attribute على الأقل على حرف واحد.',
    'mixed' => 'يجب أن يحتوي حقل :attribute على حرف واحد كبير وحرف واحد صغير على الأقل.',
    'numbers' => 'يجب أن يحتوي حقل :attribute على رقم واحد على الأقل.',
    'symbols' => 'يجب أن يحتوي حقل :attribute على رمز واحد على الأقل.',
    'uncompromised' => 'تم العثور على :attribute في تسريب بيانات. يرجى اختيار :attribute مختلف.',
],
'present' => 'يجب أن يكون حقل :attribute موجودًا.',
'prohibited' => 'حقل :attribute محظور.',
'prohibited_if' => 'حقل :attribute محظور عند :other يس',

'prohibited_unless' => 'حقل :attribute ممنوع ما لم يكن :other موجودًا في :values.',
'prohibits' => 'حقل :attribute يمنع وجود :other.',
'regex' => 'صيغة حقل :attribute غير صالحة.',
'required' => 'حقل :attribute مطلوب.',
'required_array_keys' => 'حقل :attribute يجب أن يحتوي على مدخلات لـ :values.',
'required_if' => 'حقل :attribute مطلوب عندما يكون :other هو :value.',
'required_if_accepted' => 'حقل :attribute مطلوب عند قبول :other.',
'required_unless' => 'حقل :attribute مطلوب ما لم يكن :other موجودًا في :values.',
'required_with' => 'حقل :attribute مطلوب عندما تكون :values موجودة.',
'required_with_all' => 'حقل :attribute مطلوب عندما تكون :values موجودة.',
'required_without' => 'حقل :attribute مطلوب عندما تكون :values غير موجودة.',
'required_without_all' => 'حقل :attribute مطلوب عندما لا يكون أيًا من :values موجودًا.',
'same' => 'حقل :attribute يجب أن يكون مطابقًا لـ :other.',
'size' => [
    'array' => 'حقل :attribute يجب أن يحتوي على :size عناصر.',
    'file' => 'حقل :attribute يجب أن يكون حجمه :size كيلوبايت.',
    'numeric' => 'حقل :attribute يجب أن يكون :size.',
    'string' => 'حقل :attribute يجب أن يكون طوله :size أحرف.',
],
'starts_with' => 'حقل :attribute يجب أن يبدأ بأحد القيم التالية: :values.',
'string' => 'حقل :attribute يجب أن يكون نصًا.',
'timezone' => 'حقل :attribute يجب أن يكون منطقة زمنية صالحة.',
'unique' => 'قيمة حقل :attribute مُستخدمة بالفعل.',
'uploaded' => 'فشل تحميل ملف الحقل :attribute.',
'uppercase' => 'حقل :attribute يجب أن يكون بالأحرف الكبيرة.',
'url' => 'حقل :attribute يجب أن يكون عنوان URL صالح.',
'ulid' => 'حقل :attribute يجب أن يكون رقم ULID صالح.',
'uuid' => 'حقل :attribute يجب أن يكون UUID صالح.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
