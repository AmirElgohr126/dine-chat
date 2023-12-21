<?php
namespace App\Http\Controllers\Dashboards\DashboardRestaurant\User\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Auth\AddRestaurantRequest;
use App\Models\ApplcationForRestaurant;
use Exception;

class AddRestaurantOrder extends Controller
{
    public function OrderAddRestaurant(AddRestaurantRequest $request)
    {
        try{
            $data = $request->validated();
            extract($data);
            $applcation = ApplcationForRestaurant::create([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email'=> $email,
                'restaurant_name' => $restaurant_name,
                'order' => $order
            ]);
            if ($applcation)
            {
                return finalResponse('success',200,$data);
            }
            throw new Exception("somethings wrong happen", 400);

        }catch(Exception $e){
            return finalResponse('faield',400,null,null,$e->getMessage());
        }


    }
}

?>

