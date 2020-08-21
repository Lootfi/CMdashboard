<?php

namespace App\Http\Controllers\Api\Front\Auth;

use App\ClientPaymentAuthorization;
use App\Http\Controllers\Controller;
use App\Jobs\CapturePayment;
use App\Models\Artist;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function validateEmail()
    {
        $validation = Validator::make(request()->only(['email']), [
            'email' => ['required', 'email', 'max:255', 'unique:users']
        ]);

        if ($validation->fails()) return response()->json(['status' => 'invalid', 'errors' => $validation->errors()]);

        $user = new Artist();

        $user->email = request('email');
        $user->slug = explode('@', request('email'))[0] . '-' . substr(md5(mt_rand()), 0, 6);
        $user->save();


        return response()->json(['status' => 'valid', 'email' => $user->email]);
        // $curl = curl_init();
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'https://api.clearout.io/v2/email_verify/instant',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 30,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => '{"email": "' . request('email') . '"}',
        //     CURLOPT_HTTPHEADER => array(
        //         "Content-Type: application/json",
        //         "Authorization: Bearer:" . env('CLEAROUT_TOKEN')
        //     ),
        // ));

        // $response = curl_exec($curl);
        // $err = curl_error($curl);

        // curl_close($curl);

        // if ($err) {
        //     return response()->json(['error' => $err]);
        // } else {
        //     $response = json_decode($response, true);
        //     return response()->json(['status' => $response['data']['status']]);
        // }
        // return;
    }

    public function paypalPaymentConfirmed()
    {
        if (!$artist = Artist::where('email', request('email'))->first()) {
            return response()->json(['error' => 'No user with given email'], 404);
        }

        $artist->payment_method = 'paypal_' . request('order_id');
        $artist->name = request('name');
        $artist->payment_authorized = true;
        $artist->payment_confirmed = false;
        $artist->status = true;
        $artist->save();

        $token = $this->login($artist);

        $this->createPaymentAuth(request()->only('order_id', 'auth_id'));

        // Queue::later(Carbon::now()->addSeconds(20), new CapturePayment($artist));

        CapturePayment::dispatch($artist)->delay(Carbon::now()->addSeconds(20))->onConnection('database');


        return response()->json(['success' => true, 'user' => $token->original['user'], 'access_token' => $token->original['access_token']]);
    }

    public function setupProfile(Request $request)
    {
        $artist = $request->user('clients');

        $artist->name = request('name');
        $artist->password = Hash::make(request('password'));

        $artist->save();
    }



    public function login($user)
    {
        if (!$token = Auth::guard('clients')->login($user)) {

            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        Auth::guard('clients')->logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.'
        ], 200);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('clients')->factory()->getTTL() * 60,
            'user' => Auth::guard('clients')->user(),
        ]);
    }

    protected function createPaymentAuth($info)
    {
        ClientPaymentAuthorization::create([
            'client_id' => Auth::guard('clients')->user()->id,
            'order_id' => $info['order_id'],
            'auth_id' => $info['auth_id']
        ]);
    }
}
