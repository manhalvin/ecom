<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;
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
            ->where('email', $userGoogle->getEmail())
            ->first();

        if (!$user) {
            $user = new User();
            $user->name = $userGoogle->getName();
            $user->email = $userGoogle->getEmail();
            $user->provider = $provider;
            $user->provider_id = $providerId;
            $user->group_id = 1;
            $user->password = Hash::make('123456789');
            $user->save();
        } else {
            $userProviderId = User::where('provider_id', $providerId)->first();
            if (!$userProviderId) {
                User::where('provider', $provider)
                    ->where('email', $userGoogle->getEmail())
                    ->update(['provider_id' => $providerId]);
            }
        }

        $userId = $user->id;
        Auth::loginUsingId($userId);

        return redirect()->route('admin.users.index');
    }

    // public function login(Request $request)
    // {
    //     $email = $request->email;
    //     $password = $request->password;

    //     $checkLogin = Auth::attempt([
    //         'email' => $email,
    //         'password' => $password,
    //     ]);

    //     if ($checkLogin) {
    //         $user = Auth::user();

    //         $token = $user->createToken('auth_token')->plainTextToken;
    //         $response = [
    //             'status_code' => 200,
    //             'token' => $token,
    //         ];

    //     } else {
    //         $response = [
    //             'status_code' => 401,
    //             'status' => 'Unauthorized',
    //         ];
    //     }

    //     return $response;
    // }

    public function login(Request $request)
    {
        // Passport Laravel
        $email = $request->email;
        $password = $request->password;

        $checkLogin = Auth::attempt([
            'email' => $email,
            'password' => $password,
        ]);

        if ($checkLogin) {
            $user = Auth::user();

            // 1. Laravel sanctum
            // $token = $user->createToken('auth_token')->plainTextToken;

            // 2. Laravel Passport
            // $tokenResult = $user->createToken('auth_api');
            // // Thiết lập expires
            // $token = $tokenResult->token;
            // $token->expire_at = Carbon::now()->addMinutes(60);

            // // Trả về access token
            // $accessToken = $tokenResult->accessToken;

            // // Trả về expires
            // $expires = Carbon::parse($token->expire_at)->toDateString();

            // $response = [
            //     'status_code' => 200,
            //     'token' => $accessToken,
            //     'expires' => $expires
            // ];

            $client = Client::where('password_client', 1)->first();
            if ($client) {
                $clientSecret = $client->secret;
                $clientId = $client->id;

                $response = Http::asForm()->post('http://127.0.0.1:8000/Laravel/Auth/oauth/token', [
                    'grant_type' => 'password',
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'email' => $email,
                    'password' => $password,
                    'scope' => '',
                ]);
            }

        } else {
            $response = [
                'status_code' => 401,
                'status' => 'Unauthorized',
            ];
        }

        return $response;
    }

    public function getToken(Request $request)
    {
        $user = $request->user();
        // $user = User::find(1);

        // $user->tokens: Truy xuất bảng personal_access_tokens
        foreach ($user->tokens as $token) {
            // echo $token->token;
        }

        // $user->tokens()->delete();
        // $user->tokens()->where('id', 4)->delete();
        // dd($user->currentAccessToken());
        $user->currentAccessToken()->delete();
    }

    public function refreshToken(Request $request)
    {
        // if ($request->header('authorization')) {
        //     $hashToken = $request->header('authorization');

        //     $hashToken = str_replace('Bearer', '', $hashToken);
        //     $hashToken = trim($hashToken);

        //     $token = PersonalAccessToken::findToken($hashToken);

        //     if ($token) {
        //         $tokenCreated = $token->created_at;
        //         $expire = Carbon::parse($tokenCreated)
        //             ->addMinute(config('sanctum.expiration'));
        //         if (Carbon::now() >= $expire) {
        //             $tokenId = $token->id;
        //             $userId = $token->tokenable_id;

        //             $user = User::find($userId);
        //             $user->tokens()->delete();
        //             $newToken = $user->createToken('auth_token')->plainTextToken;
        //             $response = [
        //                 'status_code' => 200,
        //                 'token' => $newToken,
        //             ];
        //         } else {
        //             $response = [
        //                 'status_code' => 200,
        //                 'token' => 'Token expires',
        //             ];
        //         }

        //     } else {
        //         $response = [
        //             'status_code' => 401,
        //             'token' => 'Unauthorized',
        //         ];
        //     }
        //     return $response;
        // }
        $client = Client::where('password_client', 1)->first();
        if ($client) {
            $clientSecret = $client->secret;
            $clientId = $client->id;
            $refreshToken = $request->refresh;

            $response = Http::asForm()->post('/oauth/token', [
                'grant_type' => 'refresh_token',
                'refresh_token' => $refreshToken,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'scope' => '',
            ]);

            return $response;
        }
    }

    public function passportToken(Request $request)
    {
        $user = User::find(1);
        $tokenNew = $user->createToken('auth_api');

        // table: oauth_access_tokens

        // Thiết lập expires
        $token = $tokenNew->token;
        $token->expire_at = Carbon::now()->addMinutes(60);

        // Trả về access token
        $accessToken = $tokenNew->accessToken;

        // Trả về expires
        $expires = Carbon::parse($token->expire_at)->toDateString();
        $response = [
            'access_token' => $accessToken,
            'expires' => $expires,
        ];

        return $response;

    }

    public function logout()
    {
        $user = Auth::user();
        $user->token()->revoke();
        $response = [
            'status_code' => 200,
            'status' => 'Logout',
        ];
        return $response;
    }
}
