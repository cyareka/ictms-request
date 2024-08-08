<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Registers a new admin user.
     *
     * This function validates the request data, ensuring the email domain is @dswd.gov.ph.
     * It then creates a new user with the provided data and stores the email of the user who added the new admin.
     * If the registration is successful, it redirects to the admin dashboard with a success message.
     * If an error occurs, it redirects back to the registration page with an error message.
     *
     * @param \Illuminate\Http\Request $request The HTTP request object containing form data.
     * @return \Illuminate\Http\RedirectResponse The response object redirecting to the appropriate route with a success or error message.
     */
    public function register(Request $request)
    {
        // Custom validation rule for DSWD email
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/^[a-zA-Z0-9._%+-]+@dswd\.gov\.ph$/', $value)) {
                        $fail('The email domain must be @dswd.gov.ph.');
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'added_by' => Auth::user()->email, // Store the email of the user who added the new admin
            ]);

            // Set success flash message
            return redirect()->route('admin.dashboard')->with('success', 'Admin registered successfully.');
        } catch (\Exception $e) {
            // Set error flash message
            return redirect()->route('register')->with('error', 'Failed to register admin.');
        }
    }

    /**
     * Displays the admin dashboard.
     *
     * This function retrieves all admin users and passes them to the dashboard view.
     *
     * @return \Illuminate\View\View The view object displaying the admin dashboard.
     */
    public function dashboard()
    {
        $admins = User::where('role', 'admin')->get(); // Get all admin users
        return view('admin.dashboard', compact('admins'));
    }
}
