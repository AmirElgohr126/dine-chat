<?php
namespace App\Http\Controllers\V1\App\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DeletedAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller{
    public function view()
    {
        // Pass the user data to the view
        return view('Privacy.DeleteAccount');
    }


    public function delete(Request $request)
    {
        $request->validate([
            'username' => ['required', 'exists:users,user_name'],
            'password' => 'required',
            'reason' => ['required', 'string', 'in:privacy_concerns,not_useful,prefer_another_service,too_expensive,poor_customer_service,found_better_alternative,other'],
            'feedback' => 'nullable|string|max:1000',
        ]);

        // Retrieve the user based on the provided username
        $user = User::where('user_name', $request->username)->first();

        // Check if the user is found and the password matches
        if ($user && Hash::check($request->input('password'), $user->password)) {
            // Save the deletion info in deleted_accounts table
            DeletedAccount::create([
                'user_id' => $user->id,
                'username' => $user->user_name,
                'email' => $user->email,
                'reason' => $request->input('reason'),
                'feedback' => $request->input('feedback'),
                'deleted_at' => now()
            ]);

            // Delete the user
            $user->delete();

            // Redirect to a confirmation page or route
            return redirect('/welcome')->with('status', 'Your account has been successfully deleted.');
        } else {
            return back()->withErrors(['password' => 'The provided password does not match our records.']);
        }
    }
}
