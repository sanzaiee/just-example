<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendVerifyEmailWithOtp;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        return Auth::attempt(
            array_merge($this->credentials($request), ['status' => 1]),
            $request->filled('remember')
        );
    }

    public function login(Request $request)
    {
        //$start = microtime(true);

        $this->validate($request, ['email' => 'required|email']);
        $email = strtolower($request->email);
        $user = User::where('email', $email)->first();
        if (! $user) {
            return redirect()->back()->with('error', 'Invalid email');
        }

        if(!$user->status){
            return redirect()->back()->with('error', 'You are`t authorized yet. Please contact to administration.');
        }

        $otp = rand(100000, 999999);
        $user->update([
            'otp' => $otp,
            'expired_at' => now()->addMinutes(10),
        ]);
        //Mail::to($user->email)->send(new SendVerifyEmailWithOtp($user, $otp));

        $response = Http::withHeaders([
            'api-key' => env('BREVO_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', [
            'sender' => [
                'email' => 'practise.saroj@gmail.com',
                'name' => 'Practise Saroj',
            ],
            'to' => [
                [
                    'email' => $user->email,
                    'name' => $user->name,
                ],
            ],
            'subject' => 'Verify Your Email',
            'htmlContent' => "
                <p>Hello {$user->name},</p>
                <p>Your OTP is:</p>
                <h2>{$otp}</h2>
                <p>This OTP will expire soon.</p>
            ",
        ]);

        if (! $response->successful()) {
            dd($response->json());
        }

        //$executionTime = microtime(true) - $start;
        // dump('Execution time: ' . $executionTime . ' seconds');

        return redirect()->route('verify.otp', $user->email)->with('success', 'OTP sent to your email');
    }

    public function verifyOtp($emails)
    {
        $email = strtolower($emails);
        $user = User::where('email', $email)->first();
        if (! $user) {
            return redirect()->back()->with('error', 'User not found');
        }

        return view('auth.verify', compact('user'));
    }

    public function verifyOtpPost(Request $request, $emails)
    {
        $email = strtolower($emails);
        $user = User::where('email', $email)->first();
        if (! $user) {
            return redirect()->back()->with('error', 'User not found');
        }
        if ($user->otp !== $request->otp) {
            return redirect()->back()->with('error', 'Invalid OTP');
        }
        if ($user->expired_at && $user->expired_at < now()) {
            return redirect()->back()->with('error', 'OTP expired');
        }
        $user->update(['status' => 1, 'email_verified_at' => now()]);
        Auth::login($user);

        return redirect()->route('user.dashboard')->with('success', 'Successfully logged in');
    }
}
