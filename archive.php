
    // public function sendNotificationNow(Request $request)
    // {
    //     $user = $request->user('restaurant');
    //     $accessToken = app('firebase.access_token'); // Get the access token

    //     $restaurantId = $user->restaurant_id; // replace with your desired restaurant_id
    //     $uniqueUserIds = UserAttendance::where('restaurant_id', $restaurantId)
    //         ->distinct()
    //         ->pluck('user_id');
    //     $deviceTokens = User::whereIn('id', $uniqueUserIds)
    //         ->pluck('device_token')->toArray(); // 'device_tokens' in table users

    //     $projectId = 'dinechate';
    //     $curl = curl_init();
    //     $Notification = Notification::where('restaurant_id', $restaurantId)->where('id',$request->id)->first();

    //     $postData = [
    //         'message' => [
    //             'notification' => [
    //                 'title' => $Notification->title,
    //                 'body' => $Notification->message,
    //                 'image' => retriveMedia() . $Notification->photo
    //             ],
    //             'registration_ids' => $deviceTokens, // Array of device tokens
    //         ]
    //     ];

    //     $headers = [
    //         'Authorization: Bearer ' . $accessToken,
    //         'Content-Type: application/json'
    //     ];

    //     curl_setopt_array($curl, [
    //         CURLOPT_URL => "https://fcm.googleapis.com/v1/projects/$projectId/messages:send",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_CUSTOMREQUEST => "POST",
    //         CURLOPT_POSTFIELDS => json_encode($postData),
    //         CURLOPT_HTTPHEADER => $headers,
    //     ]);

    //     $response = curl_exec($curl);
    //     if (curl_errno($curl)) {
    //         $error_msg = curl_error($curl);
    //     }
    //     curl_close($curl);
    //     return $response;
    // }
<?php


use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

if (!function_exists('getLocales')) {
    /**
     * @return array array of supported locales
     */
    function getLocales()
    {
        return ['ar', 'en'];
    }
}

if (!function_exists('translateAttributes')) {
    function translateAttributes($attributesWithTranslatedText = [], $prefix = 'form'): array
    {
        if (!is_null($prefix))
            $prefix .= '.';
        foreach ($attributesWithTranslatedText as $attribute => $translatedText) {
            if (str($attribute)->endsWith('.*')) {
                foreach (getLocales() as $locale)
                    $translatedAttributes[$prefix . str($attribute)->replaceFirst('*', $locale)] = __($translatedText);
            } else {
                $translatedAttributes[$prefix . $attribute] = __($translatedText);
            }
        }
        return $translatedAttributes ?? [];
    }
}

if (!function_exists('dynamicMultiLangValidationRules')) {
    /**
     * @param $attributesAndRules array array of multilang fileds as keys and their validation rules as values
     * @param $bindOtherAttibutesRules array|null array of other fields as keys and theris validation roles as values
     * @param string $prefix a prefixed keyword attached before langauge attributes
     * @return array
     */
    function dynamicMultiLangValidationRules($attributesAndRules, $bindOtherAttibutesRules = null, $prefix = 'form')
    {
        if (!is_null($prefix))
            $prefix .= '.';
        foreach (getLocales() as $locale) {
            foreach ($attributesAndRules as $attribute => $rules) {
                $validationRules[$prefix . $attribute . '.' . $locale] = $rules;
            }
        }
        if (is_array($bindOtherAttibutesRules)) {
            foreach ($bindOtherAttibutesRules as $attribute => $rules) {
                $validationRules[$attribute] = $rules;
            }
        }
        return $validationRules ?? [];
    }
}

if (!function_exists('createdAt')) {
    /**
     * @return string formated date
     */
    function createdAt($created_at, $shouldParse = false)
    {
        if ($shouldParse)
            $created_at = Carbon::parse($created_at);
        return $created_at->toDayDateTimeString() . ' - ' . $created_at->diffForHumans();
    }
}

if (!function_exists('retrieveFromCache')) {
    function retrieveFromCache(string $key, $model = null, $relations = null, $order = "ASC", $isCollection = true)
    {
        return cache()->rememberForever($key, function () use ($model, $relations, $order, $isCollection) {
            if ($model == null)
                return null;
            $model = "\\App\\Models\\" . $model;
            $records = $model::query();
            if ($relations != null)
                $records = $records->with($relations);
            if ($order != "ASC")
                $records = $records->latest();
            if (!$isCollection)
                return $records->first();
            return $records->get();
        });
    }
}

if (!function_exists('removeFromCache')) {
    function removeFromCache(string $key)
    {
        cache()->forget($key);
    }
}

if (!function_exists('reCache')) {
    function reCache(string $key, $model = null, $relations = null, $order = "ASC", $isCollection = true)
    {
        removeFromCache($key);
        return retrieveFromCache($key, $model, $relations, $order, $isCollection);
    }
}

if (!function_exists('encryptData')) {
    /**
     * @param $data string raw data to encrypt
     * @return string encrypted data
     */
    function encryptData(string $data, string $key): string
    {
        $encrypter = new Encrypter($key, Config::get('app.cipher'));
        return $encrypter->encrypt($data);
    }
}

if (!function_exists('decryptData')) {
    /**
     * @param $data string encrypted data to decrypt
     * @return string decrypt data
     */
    function decryptData(string $data, string $key): string
    {
        $encrypter = new Encrypter($key, Config::get('app.cipher'));
        return $encrypter->decrypt($data);
    }
}

if (!function_exists('randomCode')) {
    /**
     * @param $length int size of randomly generated number max size 100
     * @param $type int 0 for alphanumeric , 1  for numeric only, 2 for alphapitical only
     * @return mixed
     */
    function randomCode($length, $type = 0)
    {
        $min_lenght = 1;
        $max_lenght = 100;
        $bigL = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $smallL = "abcdefghijklmnopqrstuvwxyz";
        $number = "123456789";
        $bigB = str_shuffle($bigL);
        $smallS = str_shuffle($smallL);
        $numberS = str_shuffle($number);
        $subA = substr($bigB, 0, 5);
        $subB = substr($bigB, 6, 5);
        $subC = substr($bigB, 10, 5);
        $subD = substr($smallS, 0, 5);
        $subE = substr($smallS, 6, 5);
        $subF = substr($smallS, 10, 5);
        $subG = substr($numberS, 0, 5);
        $subH = substr($numberS, 6, 5);
        $subI = substr($numberS, 9, 5);
        switch ($type) {
            case 1:
                $RandCode1 = str_shuffle($subG . $subH . $subI);
                break;
                case 2:
                    $RandCode1 = str_shuffle($subA . $subD . $subB . $subF . $subC . $subE);
                    break;
                    default:
                        $RandCode1 = str_shuffle($subA . $subD . $subB . $subF . $subC . $subE . $subG . $subH . $subI);
                        break;
        }
        $RandCode2 = str_shuffle($RandCode1);
        $RandCode = $RandCode1 . $RandCode2;

        if ($length > $min_lenght && $length < $max_lenght) {
            $CodeEX = substr($RandCode, 0, $length);
        } else {
            $CodeEX = $RandCode;
        }
        return $CodeEX;
    }
}

if (!function_exists('zipFiles')) {
    /**
     * @param $folder string output folder name
     * @param $files array array of files
     * @return void
     */
    function zipFiles($folder, $files)
    {
        $zip = new ZipArchive();
        if ($zip->open(public_path($folder), ZipArchive::CREATE | ZipArchive::OVERWRITE) == TRUE) {
            foreach ($files as $value) {
                $relativeName = basename($value);
                $zip->addFile($value, $relativeName);
            }
            $zip->close();
        }
    }
}

if (!function_exists('generateAPIKey')) {
    function generateAPIKey()
    {
        $data['api_key'] = encryptData(Config::get('b2b.root_key'), Config::get('b2b.private_key'));
        Storage::disk('local')->put('api_key.json', json_encode($data));
    }
}

if (!function_exists('readAPIKey')) {
    function readAPIKey()
    {
        $apiKey = json_decode(Storage::disk('local')->get('api_key.json'), true);
        return $apiKey['api_key'];
    }
}

if (!function_exists('sendFCMNotiffication')) {
    /**
     * @param $folder string output folder name
     * @param $files array array of files
     * @return void
     */
    function sendFCMNotiffication($tokens, $title, $message, $topic, $otherData = null, $typeData = true)
    {
        if ($otherData == null)
            $otherData = array();
        $otherData['topic'] = $topic;
        $url = 'https://fcm.googleapis.com/fcm/send';
        $serverKey = env('FCM_SERVER_KEY');
        $data = [
            // "topic" => $topic,
            "registration_ids" => $tokens,
        ];
        if ($typeData) {
            $otherData["body"] = $message;
            $otherData["title"] = $title;
            $data["data"] = $otherData;
        } else {
            $data["notification"] = [
                "body" => $message,
                "title" => $title,
            ];
            $data["data"] = $otherData;
        }
        $encodedData = json_encode($data);

        $headers = [
            "Authorization: key=" . $serverKey,
            "Content-Type: application/json",
            'Accept: application/json',
            "project_id" => "",
            "sender_id" => 0,
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        // Close connection
        curl_close($ch);
        // Log::info("push {$result}/nerror /n" . curl_error($ch) . "/nResponse /n" . curl_getinfo($ch, CURLINFO_HTTP_CODE));
        if ($result === FALSE) {
            // die('Curl failed: ' . curl_error($ch));
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('shuffleArray')) {
    function shuffleArray($answers)
    {
        $array = array_filter(json_decode($answers, true), function ($value) {
            return $value !== null;
        });
        shuffle($array);
        return $array;
    }
}

if (!function_exists('searchMultipleLanguages')) {
    /**
     * search multi language database columns
     *
     * @param mixed $query elquent query builder
     * @param mixed $field database column
     * @param mixed $seach search value
     * @return mixed
     */
    function searchMultipleLanguages($query, $field, $search)
    {
        return $query->where(function ($query) use ($field, $search) {
            $locales = getLocales();
            if (!empty($locales)) {
                $query->where($field . '->' . $locales[0], 'LIKE', '%' . $search . '%');
                if (!empty(array_slice($locales, 1))) {
                    foreach (array_slice($locales, 1) as $locale) {
                        $query->orWhere($field . '->' . $locale, 'LIKE', '%' . $search . '%');
                    }
                }
            }
        });
    }
}

if (!function_exists('stringfyErrors')) {
    function stringfyErrors($errors)
    {
        $groupedErrors = [];
        foreach (Arr::dot($errors) as $key => $value) {
            $groupedErrors[Str::before($key, '.')][] = $value;
            //            $groupedErrors[] = Str::before($key, '.') . "**" . $value;
        }
        return $groupedErrors;
    }
}

if (!function_exists('apiSuccess')) {
    function apiSuccess($data, $pagination = [], $extras = [], $message = null, $code = 200)
    {
        return response()->apiResponse($data, array(), $pagination, $extras, $message ?? __('Data Found'), true, $code);
    }
}

if (!function_exists('apiErrors')) {
    function apiErrors($errors, $extras = [], $message = null, $code = 200)
    {
        return response()->apiResponse(array(), $errors, [], $extras, $message ?? $errors->first(), false, $code);
    }
}

if (!function_exists('sendSMS')) {
    function sendSMS($message, $mobile)
    {
        $encodedauthdata = "";
        $result = array();
        if (config('b2b.sms') == "local") {
            $result["sms"] = true;
            $result["smsapi"] = true;
        } else {
            $guzzelClient = new Client();
            $response = $guzzelClient->request('POST', 'end-point', [
                'json' => [
                    "account_id" => 0,
                    "text" => "{$message}",
                    "msisdn" => "2{$mobile}",
                    "sender" => "sender-name",
                ],
                [
                    'connect_timeout' => 30
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Basic ' . $encodedauthdata,
                ]
            ]);
            if ($response->getStatusCode() === 200) {
                $data = (string)$response->getBody();
                $data = json_decode($data, true);
                $sms = filter_var($data['status'], FILTER_VALIDATE_BOOLEAN);
                $smsAPI = true;
            } else {
                $sms = false;
                $smsAPI = false;
            }
            $result["sms"] = $sms;
            $result["smsapi"] = $smsAPI;
        }
        return $result;
    }
}


if (!function_exists('userCan')) {
    function userCan(mixed $permissions)
    {
        if (is_array($permissions))
            $permissions = implode("|", $permissions);
        else
            $permissions = "|" . $permissions;
        return auth('sanctum')->hasUser()
            ? auth('sanctum')->user()->can('role_or_permission:super-admin' . $permissions)
            : false;
    }
}
?>
