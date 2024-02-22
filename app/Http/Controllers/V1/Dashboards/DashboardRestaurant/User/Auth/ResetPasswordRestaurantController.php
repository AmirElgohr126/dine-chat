<?php
namespace App\Http\Controllers\V1\Dashboards\DashboardRestaurant\User\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Models\RestaurantUser;
use App\Mail\OtpRestaurantMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ResetPasswordRestaurantController extends Controller
{
    public function sendOTP(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email', 'exists:restaurant_users,email']
            ]);
            $email = $request->email;
            $user = RestaurantUser::where('email', $email)->first();
            $generatedOtp = rand(100000, 999999);
            if ($user->otp) {
                $user->otp->delete();
            }
            $otp = $user->otp()->create([
                'otp' => $generatedOtp,
                'end_at' => now()->addHour()
            ]);
            $expire_at = $otp->end_at;
            $otp = $otp->otp;
            Mail::to($user->email)->send(new OtpRestaurantMail($user->name, $otp, $expire_at));
            return finalResponse('success', 200, ["message" => __('errors.check_mail'), 'email' => $user->email]);

        } catch (Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }
    public function resetpassword(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'email', 'exists:restaurant_users,email'],
                'otp' => ['required', 'integer'],
                'password' => ['required', 'confirmed', 'string']
            ]);
            $email = $request->email;
            $user = RestaurantUser::where('email', $email)->first();
            $otp = $user->otp->otp;
            if ($otp == $request->otp) {
                $user->otp->delete();
                $user->password = Hash::make($request->password);
                $user->save();
                return finalResponse('success', 200, __('errors.password_changed_success'));
            }
            throw new Exception(__('errors.falied_otp'), 400);
        } catch (Exception $e) {
            return finalResponse('failed', 400, null, null, $e->getMessage());
        }
    }
}


