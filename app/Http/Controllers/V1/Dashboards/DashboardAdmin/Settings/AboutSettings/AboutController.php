<?php

namespace App\Http\Controllers\V1\Dashboards\DashboardAdmin\Settings\AboutSettings;

use Illuminate\Http\Request;
use App\Models\AboutApplication;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

/**
 * settings controller anout abb
 */
class AboutController extends Controller
{


    /**
     * update AboutUs
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAboutUs(Request $request)
    {
        $validatedData = $request->validate([
            'about_us' => 'required|string',
        ]);

        $aboutApplication = AboutApplication::firstOrCreate([]);
        $aboutApplication->about_us = $validatedData['about_us'];
        $aboutApplication->save();
        return finalResponse('success',200,'About Us updated successfully!');
    }
    /**
     * update Terms
     * @param Request $request
     * @return JsonResponse
     */
    public function updateTerms(Request $request)
    {
        $validatedData = $request->validate([
            'terms_conditions' => 'required|string',
        ]);

        $aboutApplication = AboutApplication::firstOrCreate([]);
        $aboutApplication->about_us = $validatedData['terms_conditions'];
        $aboutApplication->save();
        return finalResponse('success', 200, 'About Us updated successfully!');
    }



    /**
     * privacy_policy
     * @param Request $request
     * @return JsonResponse
     */
    public function updatePrivacyPolicy(Request $request)
    {
        $validatedData = $request->validate([
            'privacy_policy' => 'required|string',
        ]);

        $aboutApplication = AboutApplication::firstOrCreate([]);
        $aboutApplication->about_us = $validatedData['privacy_policy'];
        $aboutApplication->save();
        return finalResponse('success', 200, 'About Us updated successfully!');
    }


}
