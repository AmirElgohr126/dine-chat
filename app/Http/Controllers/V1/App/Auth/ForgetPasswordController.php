<?php
namespace App\Http\Controllers\V1\App\Auth;


use App\Http\Controllers\Controller;
use App\Mail\OtpUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;


class ForgetPasswordController extends Controller
{
    /**
     * Send OTP to the user's email for password reset.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forgetPasswordSendOtp(Request $request): JsonResponse
    {
        // Validate the email field
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        // Check if the user has an otp and it has not expired.
        if ($user->otp && !$user->isOtpExpiry()) {
            return finalResponse('failed', 401, null, null, __('errors.otp_not_expired'));
        }
        $user->otp()->delete();
        $otp = $this->generateOtp();
        $expiresAt = now()->addMinutes(5);
        $user->otp()->create([
            'otp' => $otp,
            'expires_at' => $expiresAt,
        ]);
        $fullName = $user->first_name . ' ' . $user->last_name;
        Mail::to($user->email)->send(new OtpUser($fullName, $otp, $expiresAt));

        return finalResponse('success', 200, __('errors.otp_sent'));
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function verifyOtp(Request $request) : JsonResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|numeric',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if the user has an otp and it has not expired.
        if (!$user->otp || $user->otp->isExpired()) {
            return finalResponse('failed', 401, null, null, __('errors.otp_expired'));
        }

        if ($user->otp->otp != $request->otp) {
            return finalResponse('failed', 401, null, null, __('errors.otp_invalid'));
        }
        // create temporary token for the user to reset password
        $token = JWTAuth::fromUser($user);
        $user->otp()->delete();
        return finalResponse('success', '200',['message' => __('errors.otp_verified'),'token' => $token]);
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function resetPassword(Request $request) : JsonResponse
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = JWTAuth::parseToken()->authenticate();
        $user->password = bcrypt($request->password);
        $user->save();
        // make token invalid after password reset
        JWTAuth::invalidate(JWTAuth::getToken());
        return finalResponse('success', '200', __('errors.password_reset_success'));
    }



    /**
     * Generate a random 6-digit OTP.
     * @return int
     */
    public function generateOtp() : int
    {
        return mt_rand(100000, 999999);
    }


}

