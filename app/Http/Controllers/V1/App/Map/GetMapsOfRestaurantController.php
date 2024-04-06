<?php

namespace App\Http\Controllers\V1\App\Map;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\App\Restaurant\GetMapRestaurantRequest;
use App\Models\Restaurant;
use Exception;
use Illuminate\Http\JsonResponse;


class GetMapsOfRestaurantController extends Controller
{
    /**
     * closestRestaurants function.
     *
     * Retrieve a list of restaurants based on proximity to a given location.
     *
     * @param GetMapRestaurantRequest $request
     * @return JsonResponse
     */
    public function closestRestaurants(GetMapRestaurantRequest $request): JsonResponse
    {
        try {
            // Extract parameters from the request
            $per_page = $request->per_page;
            $latitude = (float)$request->latitude;
            $longitude = (float)$request->longitude;
            $radius = (int)$request->radius;
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
        } catch (Exception $e) { // Handle exceptions and return an error response
            return finalResponse('error', 500, "Internal Server Error" . $e->getMessage(), null);
        }
    }




}
