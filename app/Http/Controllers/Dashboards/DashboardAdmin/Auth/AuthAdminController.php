<?php

namespace App\Http\Controllers\Dashboards\DashboardAdmin\Auth;

use App\Mail\OtpAdminMail;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use App\Models\OtpSuperAdmin;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\DashboardAdmin\Auth\UserResources;

/**
 * Login to Admin Dashboard Controller
 */
class AuthAdminController extends Controller
{

    /**
     * login Dashboard function
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function loginDashboard(Request $request)
    {
        //  Validate user credentials from the incoming request
        $credentials = $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|string',
        ]);
        // Attempt to generate a JWT token with the provided credentials
        $token = auth('admin')->setTTL(env('JWT_TTL'))->attempt($credentials);
        // If token generation fails, throw an exception
        if (!$token) {
            return finalResponse('failed', 400, null, null, __('errors.invalid_credentials'));
        }
        $user = auth('admin')->user();

        // Check if the user is verified
        // if (!$user->email_verified_at) {
        //     return finalResponse('failed', 405, null, null, __('errors.email_not_verified') );
        // }
        // Return a successful response with the token and user information
        return finalResponse('success', 200, ["token" => $token, "user" => new UserResources($user)]);
    }


    /**
     * register Dashboard function
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function registerDashboard(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:30',
            'user_name' => 'required|string|unique:super_admins,user_name|max:255', // Assuming 'users' is the table name , user_name columns
            'email' => 'required|email|unique:super_admins,email|max:255',
            'phone' => 'required|numeric|unique:super_admins,phone',
            'password' => [
                'required',
                'string',
                'min:8',                    // Minimum length
                'regex:/^(?=.*[A-Z])/',     // At least one uppercase letter
                'regex:/^(?=.*[a-z])/',     // At least one lowercase letter
                'regex:/^(?=.*[0-9])/',     // At least one digit
                'regex:/^(?=.*[@$!%*?&])/'
            ], // At least one special character
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:6144', // Assuming 'image' is the field type in your database
        ]);
        $data['password'] = Hash::make($data['password']); // hash password

        // Process and store the user's photo
        $photo = $data['photo'];
        $pathImage = storeFile($photo, 'user', 'public'); // helper in helper image file return path of file
        $data['photo'] = $pathImage; // Update the 'photo' field in the data data with the stored path


        $user = SuperAdmin::create($data); // Create a new user

        return finalResponse('success', 200, ["message" => __('errors.email_send'), "user_id" => $user->id]);

    }


    /**
     * send mail
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function sendMail(Request $request)
    {
        $request->validate([
            'email' => 'required|string|exists:super_admins,email',
        ]);
        $email = $request->email;
        $user = SuperAdmin::where('email', $email)->first();
        $otp = $user->otp;
        if ($otp) {
            $otp->delete();
        }
        $newOtp = rand(100000, 999999);
        $otpSuperAdmin = OtpSuperAdmin::create([
            'super_admin_id' => $user->id,
            'otp' => $newOtp,
            'expires_at' => now()->addMinutes(5)
        ]);

        Mail::to($user->email)->send(new OtpAdminMail($user->name, $otpSuperAdmin->otp, $otpSuperAdmin->expires_at));
        return finalResponse('success', 200, 'please check your mail.');
    }

    /**
     * reset password Dashboard
     * @param \Illuminate\Http\Request $request
     *  @return JsonResponse
     */
    public function resetPasswordDashboard(Request $request)
    {
        $request->validate([
            'email' => 'required|string|exists:super_admins,email',
            'otp' => 'required|min:6|integer',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = $request->email;
        $otp = $request->otp;

        $user = SuperAdmin::where('email', $email)->first();

        $otpRecord = $user->otp()->where('otp', $otp)->first();
        if (!$otpRecord) {
            return finalResponse('failed', 400, null, 'Invalid OTP.');
        }

        if (!$otpRecord) {
            finalResponse('failed', 400, null, 'Invalid OTP.');
        }

        if ($otpRecord->isExpired()) {
            $otpRecord->delete();
            finalResponse('failed', 400, null, 'expired OTP.');
        }


        $request->merge(['password' => Hash::make($request->password)]);
        $user->password = $request->password;

        $user->save();
        $otpRecord->delete();
        return finalResponse('password reset succesfully', 200, null, null);

    }
    /**
     * reset password Dashboard
     * @param \Illuminate\Http\Request $request
     *  @return JsonResponse
     */
    public function logout(Request $request)
    {
            auth('admin')->logout();
            return finalResponse('success', '200', __('errors.logout_successfully'));
    }


}
