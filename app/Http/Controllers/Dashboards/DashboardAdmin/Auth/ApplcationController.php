<?php
namespace App\Http\Controllers\Dashboards\DashboardAdmin\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\ApplcationForRestaurant;
use App\Http\Requests\Dashboard\Auth\AddRestaurantRequest;


/**
 *  register Applcation Controller
 */
class ApplcationController extends Controller
{

    /**
     * list Applacation register to dashbaord
     * @param AddRestaurantRequest $request
     * @return JsonResponse
     */
    public function OrderAddRestaurant(AddRestaurantRequest $request)
    {
        $data = $request->validated();
        extract($data);
        $applcation = ApplcationForRestaurant::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'restaurant_name' => $restaurant_name,
            'order' => $order,
            'phone' => $phone,
        ]);
        if (!$applcation) {
            return finalResponse('failed', 400, null, null, 'something error happen');
        }
        return finalResponse('success', 200, $applcation);
    }


    /**
     * list Applacation register to dashbaord
     * @param Request $request
     * @return JsonResponse
     */
    public function listApplacation(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $applcations = ApplcationForRestaurant::paginate($per_page);
        if (!$applcations) {
            return finalResponse('failed', 400, null, null, 'something error happen');
        }
        $pagnationResponse = pagnationResponse($applcations);
        return finalResponse('success', 200, $applcations->items(), $pagnationResponse);
    }


    /**
     * Accept application that registered on dashboard
     * @param Request $request
     * @return JsonResponse
     */
    public function acceptApplacation(Request $request, $applicationId)
    {
        $request->merge(['applicationId' => $applicationId]);
        $validated = $request->validate([
            'applicationId' => 'required|exists:applcation_for_restaurants,id',
        ]);
        $application = ApplcationForRestaurant::find($request->applicationId);
        if($application->status == 'accepted')
        {
            return finalResponse('failed', 400, null, null, "Application already accepted.");
        }
        $application->status = 'accepted';
        $application->save();
        return finalResponse('success', 200, 'Application accepted successfully.');
    }


    /**
     * reject Applacation that register on dashbaord
     * @param Request $request
     * @return JsonResponse
     */
    public function rejectApplacation(Request $request, $applicationId)
    {
        $request->merge(['applicationId' => $applicationId]);
        $validated = $request->validate([
            'applicationId' => 'required|exists:applcation_for_restaurants,id',
        ]);
        $application = ApplcationForRestaurant::find($request->applicationId);
        if ($application->status == 'rejected') {
            return finalResponse('failed', 400, null, null, "Application already rejected.");
        }
        $application->status = 'rejected';
        $application->save();
        return finalResponse('success', 200, 'Application rejected successfully.');
    }



}

