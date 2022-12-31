<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function loginUsingGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackFromGoogle()
    {
        $userGoogle = Socialite::driver('google')->user();
        $providerId = $userGoogle->getId();
        $provider = 'google';

        $user = User::where('provider', $provider)
            ->where('provider_id', $providerId)->first();

        if(!$user){
            $user = new User();
            $user->name = $userGoogle->getName();
            $user->email = $userGoogle->getEmail();
            $user->provider_id = $providerId;
            $user->group_id = 1;
            $user->password = Hash::make('123456789');
            $user->save();
        }

        $userId = $user->id;
        Auth::loginUsingId($userId);

        return redirect()->route('admin.users.index');
    }
}
