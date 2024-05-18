<?php

return [

    /*
    |--------------------------------------------------------------------------
    | バリデーション言語行
    |--------------------------------------------------------------------------
    |
    | 次の言語行には、バリデータークラスによって使用されるデフォルトのエラーメッセージが含まれています。
    | これらのルールの一部には複数のバージョンがあります。例えばサイズルールです。
    | これらのメッセージを自由に調整してください。
    |
    */

    'accepted' => ':attribute を承認する必要があります。',
    'accepted_if' => ':other が :value の場合、:attribute を承認する必要があります。',
    'active_url' => ':attribute は有効なURLである必要があります。',
    'after' => ':attribute は :date 以降の日付である必要があります。',
    'after_or_equal' => ':attribute は :date 以降の日付である必要があります。',
    'alpha' => ':attribute には文字のみを含めることができます。',
    'alpha_dash' => ':attribute には文字、数字、ダッシュ、およびアンダースコアのみを含めることができます。',
    'alpha_num' => ':attribute には文字と数字のみを含めることができます。',
    'array' => ':attribute は配列である必要があります。',
    'ascii' => ':attribute にはシングルバイトの英数字および記号のみを含めることができます。',
    'before' => ':attribute は :date 以前の日付である必要があります。',
    'before_or_equal' => ':attribute は :date 以前の日付である必要があります。',
    'between' => [
        'array' => ':attribute の項目数は :min から :max の間でなければなりません。',
        'file' => ':attribute のファイルサイズは :min から :max キロバイトの間でなければなりません。',
        'numeric' => ':attribute の値は :min から :max の間でなければなりません。',
        'string' => ':attribute の文字数は :min から :max の間でなければなりません。',
    ],
    'boolean' => ':attribute は true または false でなければなりません。',
    'can' => ':attribute には許可されていない値が含まれています。',
    'confirmed' => ':attribute の確認が一致しません。',
    'current_password' => 'パスワードが正しくありません。',
    'date' => ':attribute は有効な日付である必要があります。',
    'date_equals' => ':attribute は :date と等しい日付である必要があります。',
    'date_format' => ':attribute は :format の形式と一致する必要があります。',
    'decimal' => ':attribute には :decimal 小数点以下の桁数が必要です。',
    'declined' => ':attribute は辞退されなければなりません。',
    'declined_if' => ':other が :value の場合、:attribute は辞退されなければなりません。',
    'different' => ':attribute と :other は異なる必要があります。',
    'digits' => ':attribute は :digits 桁である必要があります。',
    'digits_between' => ':attribute は :min から :max 桁の間でなければなりません。',
    'dimensions' => ':attribute の画像寸法が無効です。',
    'distinct' => ':attribute に重複する値があります。',
    'doesnt_end_with' => ':attribute は次のいずれかで終わってはなりません: :values。',
    'doesnt_start_with' => ':attribute は次のいずれかで始まってはなりません: :values。',
    'email' => ':attribute は有効なメールアドレスである必要があります。',
    'ends_with' => ':attribute は次のいずれかで終わる必要があります: :values。',
    'enum' => '選択された :attribute は無効です。',
    'exists' => '選択された :attribute は無効です。',
    'extensions' => ':attribute のファイル拡張子は次のいずれかでなければなりません: :values。',
    'file' => ':attribute はファイルでなければなりません。',
    'filled' => ':attribute には値が必要です。',
    'gt' => [
        'array' => ':attribute の項目数は :value を超えなければなりません。',
        'file' => ':attribute のファイルサイズは :value キロバイトを超えなければなりません。',
        'numeric' => ':attribute の値は :value を超えなければなりません。',
        'string' => ':attribute の文字数は :value を超えなければなりません。',
    ],
    'gte' => [
        'array' => ':attribute の項目数は :value 以上でなければなりません。',
        'file' => ':attribute のファイルサイズは :value キロバイト以上でなければなりません。',
        'numeric' => ':attribute の値は :value 以上でなければなりません。',
        'string' => ':attribute の文字数は :value 以上でなければなりません。',
    ],
    'hex_color' => ':attribute は有効な16進数の色である必要があります。',
    'image' => ':attribute は画像でなければなりません。',
    'in' => '選択された :attribute は無効です。',
    'in_array' => ':attribute は :other に存在しなければなりません。',
    'integer' => ':attribute は整数でなければなりません。',
    'ip' => ':attribute は有効なIPアドレスでなければなりません。',
    'ipv4' => ':attribute は有効なIPv4アドレスでなければなりません。',
    'ipv6' => ':attribute は有効なIPv6アドレスでなければなりません。',
    'json' => ':attribute は有効なJSON文字列でなければなりません。',
    'list' => ':attribute はリストである必要があります。',
    'lowercase' => ':attribute は小文字でなければなりません。',
    'lt' => [
        'array' => ':attribute の項目数は :value 未満でなければなりません。',
        'file' => ':attribute のファイルサイズは :value キロバイト未満でなければなりません。',
        'numeric' => ':attribute の値は :value 未満でなければなりません。',
        'string' => ':attribute の文字数は :value 未満でなければなりません。',
    ],
    'lte' => [
        'array' => ':attribute の項目数は :value を超えてはなりません。',
        'file' => ':attribute のファイルサイズは :value キロバイト以下でなければなりません。',
        'numeric' => ':attribute の値は :value 以下でなければなりません。',
        'string' => ':attribute の文字数は :value 以下でなければなりません。',
    ],
    'mac_address' => ':attribute は有効なMACアドレスでなければなりません。',
    'max' => [
        'array' => ':attribute の項目数は :max を超えてはなりません。',
        'file' => ':attribute のファイルサイズは :max キロバイトを超えてはなりません。',
        'numeric' => ':attribute の値は :max を超えてはなりません。',
        'string' => ':attribute の文字数は :max 文字を超えてはなりません。',
    ],
    'max_digits' => ':attribute の桁数は :max を超えてはなりません。',
    'mimes' => ':attribute は次のタイプのファイルでなければなりません: :values。',
    'mimetypes' => ':attribute は次のタイプのファイルでなければなりません: :values。',
    'min' => [
        'array' => ':attribute の項目数は少なくとも :min でなければなりません。',
        'file' => ':attribute のファイルサイズは少なくとも :min キロバイトでなければなりません。',
        'numeric' => ':attribute の値は少なくとも :min でなければなりません。',
        'string' => ':attribute の文字数は少なくとも :min 文字でなければなりません。',
    ],
    'min_digits' => ':attribute の桁数は少なくとも :min でなければなりません。',
    'missing' => ':attribute を欠かさなければなりません。',
    'missing_if' => ':other が :value の場合、:attribute を欠かさなければなりません。',
    'missing_unless' => ':other が :value でない限り、:attribute を欠かさなければなりません。',
    'missing_with' => ':values が存在する場合、:attribute を欠かさなければなりません。',
    'missing_with_all' => ':values が存在する場合、:attribute を欠かさなければなりません。',
    'multiple_of' => ':attribute は :value の倍数でなければなりません。',
    'not_in' => '選択された :attribute は無効です。',
    'not_regex' => ':attribute の形式が無効です。',
    'numeric' => ':attribute は数値でなければなりません。',
    'password' => [
        'letters' => ':attribute には少なくとも1文字を含める必要があります。',
        'mixed' => ':attribute には少なくとも1つの大文字と1つの小文字を含める必要があります。',
        'numbers' => ':attribute には少なくとも1つの数字を含める必要があります。',
        'symbols' => ':attribute には少なくとも1つの記号を含める必要があります。',
        'uncompromised' => '入力された :attribute はデータ漏洩に含まれていました。別の :attribute を選択してください。',
    ],
    'present' => ':attribute が存在している必要があります。',
    'present_if' => ':other が :value の場合、:attribute が存在している必要があります。',
    'present_unless' => ':other が :value でない限り、:attribute が存在している必要があります。',
    'present_with' => ':values が存在する場合、:attribute が存在している必要があります。',
    'present_with_all' => ':values が存在する場合、:attribute が存在している必要があります。',
    'prohibited' => ':attribute は禁止されています。',
    'prohibited_if' => ':other が :value の場合、:attribute は禁止されています。',
    'prohibited_unless' => ':other が :values の場合を除き、:attribute は禁止されています。',
    'prohibits' => ':attribute は :other の存在を禁止します。',
    'regex' => ':attribute の形式が無効です。',
    'required' => ':attribute は必須です。',
    'required_array_keys' => ':attribute には次の項目が含まれている必要があります: :values。',
    'required_if' => ':other が :value の場合、:attribute は必須です。',
    'required_if_accepted' => ':other が受け入れられた場合、:attribute は必須です。',
    'required_if_declined' => ':other が辞退された場合、:attribute は必須です。',
    'required_unless' => ':other が :values でない限り、:attribute は必須です。',
    'required_with' => ':values が存在する場合、:attribute は必須です。',
    'required_with_all' => ':values が存在する場合、:attribute は必須です。',
    'required_without' => ':values が存在しない場合、:attribute は必須です。',
    'required_without_all' => ':values が存在しない場合、:attribute は必須です。',
    'same' => ':attribute と :other は一致する必要があります。',
    'size' => [
        'array' => ':attribute は :size 項目を含んでいる必要があります。',
        'file' => ':attribute は :size キロバイトでなければなりません。',
        'numeric' => ':attribute は :size でなければなりません。',
        'string' => ':attribute は :size 文字でなければなりません。',
    ],
    'starts_with' => ':attribute は次のいずれかで始まる必要があります: :values。',
    'string' => ':attribute は文字列でなければなりません。',
    'timezone' => ':attribute は有効なタイムゾーンでなければなりません。',
    'unique' => ':attribute は既に使用されています。',
    'uploaded' => ':attribute のアップロードに失敗しました。',
    'uppercase' => ':attribute は大文字でなければなりません。',
    'url' => ':attribute は有効なURLでなければなりません。',
    'ulid' => ':attribute は有効なULIDでなければなりません。',
    'uuid' => ':attribute は有効なUUIDでなければなりません。',

    /*
    |--------------------------------------------------------------------------
    | カスタムバリデーション言語行
    |--------------------------------------------------------------------------
    |
    | ここでは、属性に対するカスタムバリデーションメッセージを指定できます。
    | "attribute.rule" の命名規則を使用します。これにより、特定の属性ルールに対する
    | カスタム言語行を簡単に指定できます。
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'カスタムメッセージ',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | カスタムバリデーション属性
    |--------------------------------------------------------------------------
    |
    | 次の言語行は、属性プレースホルダーを「Eメールアドレス」などのより読みやすいものに
    | 置き換えるために使用されます。これにより、メッセージをより表現力豊かにすることができます。
    |
    */

    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'room_name' => '部屋名',
    ],
];
