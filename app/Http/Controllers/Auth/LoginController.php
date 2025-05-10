<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\UserBusiness;
use Illuminate\Http\Request;
use App\Models\BusinessUsers;
use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\BusinessUser;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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

    public function login(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['status' => false,  'errors' => $validator->errors()]);
        }

        $user = User::where('email', $input['email'])->first();

        if (!$user) {

            return response()->json(['status' => false, 'errors' => [
                'email' => 'The email address do not match our records'
            ]]);
        }

        if ($user->status == 0) {

            return response()->json(['status' => false, 'errors' => [
                'email' => 'Sorry! Your account was deactivated. Please contact the support team.'
            ]]);
        }

        if (auth()->attempt(array('email' => $input['email'], 'password' => $input['password'], 'status' => 1))) {

                $route = route('dashboard');

                return response()->json(['status' => true, 'message' => 'Success', 'route' => $route]);

        } else {

            return response()->json(['status' => false, 'errors' => [
                'password' => 'Password does not match.'
            ]]);
        }
    }
}
