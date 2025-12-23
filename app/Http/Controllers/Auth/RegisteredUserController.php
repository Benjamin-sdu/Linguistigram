<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $languagesConfig = config('languages');

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'languages_teach' => ['required', 'array', 'min:1', 'max:8'], 
            'languages_teach.*' => ['string', 'distinct', 'in:' . implode(',', array_values($languagesConfig))],
            'languages_learn' => ['required', 'array', 'min:1', 'max:8'],
            'languages_learn.*' => ['string', 'distinct', 'in:' . implode(',', array_values($languagesConfig))],
        ]);

        $languagesTeach = array_keys(array_intersect($languagesConfig, $request->languages_teach));
        $languagesLearn = array_keys(array_intersect($languagesConfig, $request->languages_learn));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'languages_teach' => $languagesTeach,
            'languages_learn' => $languagesLearn,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
