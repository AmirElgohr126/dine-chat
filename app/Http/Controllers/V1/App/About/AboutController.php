<?php
namespace App\Http\Controllers\V1\App\About;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\AboutApplication;
use App\Http\Controllers\Controller;

class AboutController extends Controller
{

    /**
     * Get privacy policy
     * @param Request $request
     * @return JsonResponse
     */
    public function privacyPolicy(Request $request): JsonResponse
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


    /**
     * Get terms and conditions
     * @param Request $request
     * @return JsonResponse
     */
    public function termsConditions(Request $request): JsonResponse
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



    /**
     * Get about us
     * @param Request $request
     * @return JsonResponse
     */
    public function aboutUs(Request $request): JsonResponse
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
