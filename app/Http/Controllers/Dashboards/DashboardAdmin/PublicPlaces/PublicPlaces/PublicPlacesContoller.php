<?php
namespace App\Http\Controllers\Dashboards\DashboardAdmin\PublicPlaces\PublicPlaces;

use App\Models\PublicPlace;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\DashboardAdmin\PublicPlaces\PublicPlaceResource;
use Illuminate\Support\Facades\Storage;


/**
 * Public Places Controller class contain generate public places, list
 */
class PublicPlacesContoller extends Controller
{

    /**
     * create Public Places
     * @param \Illuminate\Http\Request $request
     * @return jsonResponse
     */
    public function addPublicPlace(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'longitude' => 'required|numeric|between:-180,180',
            'latitude' => 'required|numeric|between:-90,90',
            'photo' => 'required|image|max:2048',
            'description' => 'nullable|string',
        ]);
        $publicPlace = PublicPlace::create($validated);
        $photo = $validated['photo'];
        $pathImage = storeFile($photo, "public_places/place{$publicPlace->id}", 'public');
        $publicPlace->photo = $pathImage; // Update the 'photo' field in the validated data with the stored path
        $publicPlace->save();
        return finalResponse('success', 200, 'data created successfully');

    }


    /**
     * list Public Places
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function listPublicPlace(Request $request)
    {
        $per_page = $request->per_page ?? 10;
        $publicPlaces = PublicPlace::paginate($per_page);
        if (!$publicPlaces) {
            return finalResponse('failed', 400, null, null, 'something error happen');
        }
        $publicPlaces = PublicPlaceResource::collection($publicPlaces);
        $pagnationResponse = pagnationResponse($publicPlaces);
        return finalResponse('success', 200, $publicPlaces, $pagnationResponse);
    }


    /**
     * update Public Places
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function updatePublicPlace(Request $request)
    {
        $request->merge(['id' => $request->place]);
        $validated = $request->validate([
            'name' => 'string|max:255',
            'id' => 'exists:public_places,id',
            'longitude' => 'numeric|between:-180,180',
            'latitude' => 'numeric|between:-90,90',
            'photo' => 'image|max:2048',
            'description' => 'nullable|string',
        ]);
        $publicPlace = PublicPlace::find($request->id);
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $old = $publicPlace->photo;
            $path = storeFile($photo, "public_places/place{$publicPlace->id}", 'public');
            $publicPlace->update(['photo' => $path]);
            if ($old) {
                Storage::disk('public')->delete($old);
            }
        }
        unset($validated['photo']);
        $publicPlace->update([$validated]);
        return finalResponse('success', 200, 'Public place updated successfully');
    }


    /**
     * delete Public Places
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function deletePublicPlace(Request $request)
    {
        $publicPlace = PublicPlace::find($request->id);
        if (!$publicPlace) {
            return finalResponse('failed', 404, null, null, 'Public place not found.');
        }

        $publicPlace->delete();

        return finalResponse('success', 200, 'Public place deleted successfully');
    }
}

