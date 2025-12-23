<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /** Show any user's profile. */
    public function show(User $user): View
    {
        $authUser = auth()->user();

        return view('profile.show', [
            'user' => $user,
            'followers' => $user?->followers(),
            'following' => $user?->following(),
            'isFollowing' => $authUser?->isFollowing($user) ?? false,
            'isFollowedBy' => $authUser?->isFollowedBy($user) ?? false,
        ]);
    }

    /** Redirect to the current user's settings section. */
    public function edit(): RedirectResponse
    {
        return Redirect::to(route('profile.show', ['user' => auth()->user()]).'#settings');
    }

    /** Update the user's profile information. */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('profile_picture')) {
            if ($request->user()->profile_picture) {
                \Storage::disk('public')->delete($request->user()->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $validated['profile_picture'] = $path;
        }

        if (isset($validated['hobbies'])) {
            $validated['hobbies'] = array_filter(
                array_map('trim', explode(',', $validated['hobbies'] ?? '')),
                fn ($hobby) => ! empty($hobby)
            );
        }

        $validated['languages_teach'] = $request->input('languages_teach', []);
        $validated['languages_learn'] = $request->input('languages_learn', []);

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.my')->with('status', 'profile-updated');
    }

    /** Delete the user's account. */
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

    /** Follow another user. */
    public function follow(User $user): RedirectResponse
    {
        $authUser = auth()->user();
        $authUser->follow($user);

        return back();
    }

    /** Unfollow another user. */
    public function unfollow(User $user): RedirectResponse
    {
        auth()->user()->unfollow($user);

        return back();
    }
}
