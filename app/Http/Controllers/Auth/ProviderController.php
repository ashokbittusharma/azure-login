<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;
use Carbon\Carbon;

class ProviderController extends Controller
{
    /**
     * index - Front login page
     *
     * @return void
     */
    public function index(){
        if(Auth::user()) return redirect('/dashboard');
        return view('auth/login');
    }

    /**
     * redirect : Redirect to client app to get login detail
     *
     * @param  mixed $provider
     * @return void
     */
    public function redirect($provider){
        return Socialite::driver($provider)->redirect();
    }

    /**
     * callback : callback function to get user detail from client app
     *
     * @param  mixed $provider
     * @return void
     */
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
                'user_type' => config('global.USER_TYPE.ADMIN_USER'),
                'email_verified_at' => Carbon::now()
            ]);
        }else{
            User::where('provider_id', $socialUser->id)->update(['provider_token' => $socialUser->token]);
        }
         Auth::login($user);
         return redirect('/dashboard');
       } catch (Exception $ex) {
        return redirect('/');
       }
    }

    /**
     * logout : Destroy user session
     *
     * @param  mixed $request
     * @return void
     */
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
