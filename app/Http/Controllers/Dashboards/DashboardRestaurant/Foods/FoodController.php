<?php
namespace App\Http\Controllers\Dashboards\DashboardRestaurant\Foods;

use Exception;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Foods\AddFoodRequest;
use App\Http\Requests\Dashboard\Foods\UpdateFoodRequest;
use App\Models\FoodImage;

class FoodController extends Controller
{
    public function addFood(AddFoodRequest $request)
    {
        try {
            $user = $request->user('restaurant');
            $restaurantId = $user->restaurant_id;
            $data = $request->validated();
            extract($data);

            $pathImage = storeFile($photo, "restaurant_$restaurantId/food", 'public'); // helper in helper image file return path of file
            DB::beginTransaction();
            $food = Food::create([
                'restaurant_id' => $restaurantId,
                'en' => [
                    'name' => $en['name']
                ],
                'ar' => [
                    'name' => $ar['name']
                ],
                'price' => $price,
                'status' => $status,
                'details' => $details,
            ]);
            $image = FoodImage::create([
                'food_id' => $food->id,
                'image' => $pathImage,
            ]);
            DB::commit();
            return finalResponse('success', 200, 'success added food');
        } catch (Exception $e) {
            DB::rollBack();
            return finalResponse('faield', 400, null, null, $e->getMessage());
        }
    }

    public function updateFood(UpdateFoodRequest $request)
    {
        try {
            $user = $request->user('restaurant');
            $restaurantId = $user->restaurant_id;
            $data = $request->validated();
            extract($data);
            DB::beginTransaction();
            $food = Food::findFoodById($food_id, $restaurantId);
            if (!$food) {
                throw new Exception("no food find to update");
            }
            // Update the food details
            $food->update([
                'ar' => [
                    'name' => $ar['name']
                ],
                'en' => [
                    'name' => $en['name']
                ],
                'price' => $price,
                'status' => $status,
                'details' => $details,
            ]);
            // Handle image update if needed
            if (isset($photo)) {
                $modelImage = FoodImage::where('food_id', $food->id)->first();
                $udpate = updateAndDeleteFile($photo, $modelImage, "image", "public", "restaurant_$restaurantId/food", "public");
                if (!$udpate) {
                    throw new Exception("error in storing photo", 400);
                }
            }
            DB::commit();

            return finalResponse('success', 200, 'success updated food');
        } catch (Exception $e) {
            DB::rollBack();
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }


    public function deleteFood(Request $request)
    {
        try {
            $user = $request->user('restaurant');
            $restaurantId = $user->restaurant_id;

            $foodId = $request->food_id;
            DB::beginTransaction();
            $food = Food::findFoodById($foodId, $restaurantId);

            if (!$food) {
                throw new Exception("No food found to delete");
            }
            $food->delete();
            $food->images()->delete();
            DB::commit();
            return finalResponse('success', 200, 'Success deleted food');
        } catch (Exception $e) {
            DB::rollBack();
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }


    public function getOneFood(Request $request)
    {
        try {
            $user = $request->user('restaurant');
            $food = Food::where('id', $request->id)
                ->where('restaurant_id', $user->restaurant_id)
                ->with(['rating' => function ($query) {
                    $query->selectRaw('food_id, AVG(rating) as average_rating, COUNT(id) as rating_count')
                        ->groupBy('food_id');
                }])
                ->with(['images', 'translations' => function ($query) {
                    $query->where('locale', app()->getLocale());
                }])
                ->first();
            if (!$food) {
                throw new Exception("no food found",400);
            }
            return finalResponse('success', 200, $food);
        } catch (Exception $e) {
            return finalResponse('faield', 400, null, null, $e->getMessage());
        }
    }
}

?>
