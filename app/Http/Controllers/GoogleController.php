<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver("google")->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver("google")->user();

        // dd($user);

        $findUser = User::where("google_id", $user->getId())->first();

        if ($findUser) {
            Auth::login($findUser);
            return redirect("/home");
        } else {
            $newUser = User::create([
                "name" => $user->getName(),
                "email" => $user->getEmail(),
                "password" => Hash::make(random_bytes(15)),
                "google_id" => $user->id,
                "profile_image" => $user->avatar,
            ]);

            //TODO: Token User
            $token = $newUser->createToken(User::USER_TOKEN);

            Auth::login($newUser);
            return redirect("/home");
        }
    }
}
