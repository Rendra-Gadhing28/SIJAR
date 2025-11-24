<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class ProfileController extends Controller
{

    public function index(){
         $user = Auth::user();
        // $user->where('role' != 'Admin')->get();
        $data = User::get();
        return view('user.profile',compact('user',));
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());


        $user = Auth::user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'telepon' => 'nullable|string|max:100',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        $validated['name'] = $request->name;
        $validated['email'] = $request->email;
        $validated['telepon'] = $request->telepon;

        if ($request->hasFile('profile')) {
            // if($user->profile &)
            $profile = time() . '_' . $request->file('profile')->getClientOriginalName();
            $request->file('profile')->storeAs('avatars', $profile, 'public');
            $validated['profile'] = $profile;
        }
        $user->update($validated);
        $request->user()->save();

        return Redirect::route('profile.index')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function gantiPassw(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah!');
    }
}
