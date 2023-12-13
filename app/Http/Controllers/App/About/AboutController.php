<?php
namespace App\Http\Controllers\App\About;

use Exception;
use Illuminate\Http\Request;
use App\Models\AboutApplication;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{
    public function privacyPolicy(Request $request)
    {
        try {
            $privacyPolicy = AboutApplication::where('id', 1)->select('privacy_policy')->first();
            if ($privacyPolicy) {
                return finalResponse('success', 200, $privacyPolicy);
            } else {
                throw new Exception('Privacy policy not found', 404);
            }
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), $e->getMessage());
        }
    }
    public function termsConditions(Request $request)
    {
        try {
            $terms_conditions = AboutApplication::where('id', 1)->select('terms_conditions')->first();
            if ($terms_conditions) {
                return finalResponse('success', 200, $terms_conditions);
            } else {
                throw new Exception('Terms & conditions not found', 404);
            }
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), $e->getMessage());
        }
    }
    public function aboutUs(Request $request)
    {
        try {
            $about_us = AboutApplication::where('id', 1)->select('about_us')->first();
            if ($about_us) {
                return finalResponse('success', 200, $about_us);
            } else {
                throw new Exception('about us not found', 404);
            }
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), $e->getMessage());
        }
    }

}


?>
