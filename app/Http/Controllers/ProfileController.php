<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
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
    public function update(Request $request): RedirectResponse
    {
        // Validate the request
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'program' => ['required', 'string', 'max:255'],
            'department' => ['required', 'string', 'max:255'],
            'signature' => ['nullable', 'image', 'max:2048'], // 2MB max
            'prefix' => ['nullable', 'string', 'max:10'],
            'order_title' => ['nullable', 'string', 'max:255'],
        ]);

        $user = $request->user();

        // Handle signature upload if provided
        if ($request->hasFile('signature')) {
            // Delete old signature if exists
            if ($user->signature_path) {
                Storage::delete(str_replace('storage/', '', $user->signature_path));
            }

            // Store new signature
            $signaturePath = $request->file('signature')->store('signatures', 'public');
            $validated['signature_path'] = 'storage/' . $signaturePath;
        }

        // Remove signature from validated data if no new file was uploaded
        if (!$request->hasFile('signature')) {
            unset($validated['signature']);
        }

        // Update user information
        $user->fill($validated);
        $user->save();

        return Redirect::route('profile.edit')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        // Delete signature file if exists
        if ($user->signature_path) {
            Storage::delete(str_replace('storage/', '', $user->signature_path));
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
