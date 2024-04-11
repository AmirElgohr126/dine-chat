<?php

namespace App\Http\Controllers\V1\App\Auth;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VerificationController extends Controller
{
    /**
     * Resend the email verification link to the authenticated user.
     * @param Request $request
     * @return JsonResponse
     */
    public function resend(Request $request): JsonResponse
    {
        $request->validate([
            'user_name' => 'required|string|exists:users,user_name'
        ]);
        $user = User::where('user_name','=',$request->user_name)->first();
        // Check if the user's email is already verified.
        if ($user->hasVerifiedEmail()) {
            return finalResponse('failed', 400, null, null, __('errors.email_verified'));
        }

        // Send the email verification notification to the user.
        $user->sendEmailVerificationNotification();

        // Respond with success message after sending the verification link.
        return finalResponse('success', 200, __('errors.email_send'));
    }

    /**
     * Verify the user's email using the provided verification link.
     */
    public function verify($user_id, Request $request)
    {
        // Check if the URL has a valid signature (for security purposes).
        if (!$request->hasValidSignature()) {
            return finalResponse('failed', 401, null, null, 'Invalid/Expired URL provided');
        }

        $user = User::findOrFail($user_id);
        // If the user's email is not verified, mark it as verified.
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        // Redirect the user to the home page after successful verification.
        return redirect()->to('/welcome');
    }
}
