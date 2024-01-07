<?php
namespace App\Http\Controllers\Dashboards\DashboardAdmin\GenerateUrlChairs;

use App\Http\Controllers\Controller;
use App\Models\Chair;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class GenerateUrlController extends Controller
{


    public function ChairsBasedOnRestaurant(Request $request)
    {
        $chairs = Chair::where('restaurant_id',$request->restaurant_id)->get();
        $restaurant = Restaurant::find($request->restaurant_id);

        $urls = [];
        foreach ($chairs as $chair) {
            $urls[] = $this->generateURL($restaurant,$chair);
        }
        return finalResponse('success',200,$urls);
    }



    public function generateURL($restaurant,$chair)
    {
        $data = [
            'latitude' => $restaurant->latitude,
            'longitude' => $restaurant->longitude,
            'restaurant_id' => $restaurant->id,
            'nfc_number' => $chair->nfc_number,
        ];
        $url = $baseUrl = "https://dinechat.chat/";
        $url = $baseUrl . '?' . http_build_query($data);
        return $url;
    }
}
?>
