<?php
namespace App\Http\Controllers\V2\App\About;

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
                throw new Exception(__('errors.privacy_not_found'), 404);
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
                throw new Exception(__('errors.terms_not_found'), 404);
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
                throw new Exception(__('about_not_found'), 404);
            }
        } catch (Exception $e) {
            return finalResponse('failed', $e->getCode(), $e->getMessage());
        }
    }

}
