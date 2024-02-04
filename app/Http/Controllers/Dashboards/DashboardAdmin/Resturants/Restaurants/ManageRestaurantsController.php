<?php
namespace App\Http\Controllers\Dashboards\DashboardAdmin\Resturants\Restaurants;

use Exception;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ManageRestaurantsController extends Controller
{

    /**
     * list restaurant
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function listRestaurant(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer',
        ]);
        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;
        $radius = (int) $request->radius ?? 60000;
        // Calculate the distance using Haversine formula
        $distance_result = "(6371 *
                                    acos(
                                        cos(radians($latitude)) *
                                        cos(radians(latitude)) *
                                        cos(radians(longitude) -
                                        radians($longitude)) +
                                        sin(radians($latitude)) *
                                        sin(radians(latitude))
                                        )
                                )";
        // Retrieve restaurants within the specified radius, order by distance
        $restaurants = Restaurant::select('id', 'images', 'latitude', 'longitude')
            ->selectRaw("{$distance_result} AS distance")
            ->whereRaw("{$distance_result} < ?", [$radius])->orderBy('distance')
            ->withTranslation()
            ->withCount('userAttendance')->get();

        return finalResponse('success', 200, $restaurants);  // Return the final response

    }


    /**
     * create restaurant
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function addRestaurant(Request $request)
    {
            $validated = $request->validate([
                'ar.name' => ['required', 'string'],
                'en.name' => ['required', 'string'],
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'phone' => 'required|numeric|unique:users,phone',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:6144',
            ]);
            try {

                $restaurant = Restaurant::create([
                    'latitude' => $validated['latitude'],
                    'longitude' => $validated['longitude'],
                    'phone' => $validated['phone'],
                    'en' => [
                        'name' => $validated['en']['name']
                    ],
                    'ar' => [
                        'name' => $validated['ar']['name']
                    ],
                    'hall_hight' => 800,
                    'hall_width' => 800,
                ]);

                if ($request->hasFile('photo')) {
                    $photo = $request->file('photo');
                    $path = storeFile($photo, "restaurants/restaurant{$restaurant->id}/image", 'public');
                    $restaurant->images = $path;
                    $restaurant->save();
                }
                return finalResponse('success', 200, $restaurant);
            }catch(Exception $e){
            return finalResponse('failed', 400,null,null,'another place is found in this location');
        }
    }


    /**
     * update restaurant
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function updateRestaurant(Request $request)
    {
        $vaildated = $request->validate([
            'id' => ['required', 'exists:restaurants,id'],
            'ar.name' => ['required', 'string'],
            'en.name' => ['required', 'string'],
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'phone' => 'required|numeric|unique:users,phone',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:6144',
        ]);
        $restaurant = Restaurant::find($request->id);

        $restaurant->update(
            [
                'latitude' => $vaildated['latitude'],
                'longitude' => $vaildated['longitude'],
                'phone' => $vaildated['phone'],
                'en' => [
                    'name' => $vaildated['en']['name']
                ],
                'ar' => [
                    'name' => $vaildated['ar']['name']
                ],
                'hall_hight' => 800,
                'hall_width' => 800,
            ]
        );

        if ($request->hasFile('photo')) {
            $new = $request->file('photo');
            $old = $restaurant->images;
            $path = storeFile($new, "restaurants/restaurant{$restaurant->id}/image", 'public');
            $restaurant->images = $path;
            $restaurant->save();
            if ($old) {
                Storage::disk('public')->delete($old);
            }
        }

        return finalResponse('success', 200, $restaurant);
    }


    /**
     * delete restaurant
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function deleteRestaurant(Request $request)
    {
        $restaurant = Restaurant::find($request->id);
        if (!$restaurant) {
            return finalResponse('failed', 404, null, null, 'restaurant not found.');
        }
        $restaurant->delete();

        return finalResponse('success', 200, 'restaurant deleted successfully');
    }

}


