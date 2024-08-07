<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth; // Import Auth

class AdminController extends Controller
{
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
                'role' => 'admin', // assuming you have a role field
                'added_by' => Auth::user()->email, // Store the email of the user who added the new admin
            ]);

            // Set success flash message
            return redirect()->route('admin.dashboard')->with('success', 'Admin registered successfully.');
        } catch (\Exception $e) {
            // Set error flash message
            return redirect()->route('register')->with('error', 'Failed to register admin.');
        }
    }

    public function dashboard()
    {
        // Return the admin dashboard view
        return view('admin.dashboard');
    }
}
