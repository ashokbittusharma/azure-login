<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;

class ProviderController extends Controller
{
    public function index(){
        return view('auth/login');
    }

    public function redirect($provider){
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider){
       try {
        $socialUser = Socialite::driver($provider)->user();
        //check if email used with social authentication other then Azure AD (if multi-social login functionality)
        if(User::where('email', $socialUser->getEmail())
            ->Where('provider', '<>', $provider)
            ->exists()){
             return redirect('/')->withErrors(['email' => 'This email uses different method to login']);
        }
        //check if user already exist
        $user = User::where([
            'provider' => $provider,
            'provider_id' => $socialUser->id
        ])->first();
        //else register as a new user
        if(!$user){
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'username' => User::createUserName($socialUser->getNickname()),
                'provider' => $provider,
                'provider_id' => $socialUser->id,
                'provider_token' => $socialUser->token,
                'email_verified_at' => now()
            ]);
        }

         Auth::login($user);
         return redirect('/dashboard');
       } catch (Exception $ex) {
        return redirect('/');
       }

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        // Regenerate the CSRF token
        $request->session()->regenerateToken();
        // Redirect to the intended page or a default page
        return redirect('/');
    }
}
