<?php

namespace PacketPrep\Http\Controllers\Auth;

use PacketPrep\User;
use PacketPrep\Models\User\User_Details;
use PacketPrep\Mail\ActivateUser2;
use PacketPrep\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        

        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users|regex:/(.*)@gmail\.com/i',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \PacketPrep\User
     */
    protected function create(array $data)
    {

        $url = url()->full();
        if($this->hasSubdomain($url)){
            $parsed = parse_url($url);
            $exploded = explode('.', $parsed["host"]);
            $subdomain = $exploded[0];
            
        }else
            $subdomain = null;

        $password = substr($data['phone'],0,4);
        $user = User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'client_slug' => $subdomain,
            'status'=> 1,
            'password' => bcrypt($password),
            'activation_token' => str_random(20),
        ]);


        Mail::to($user->email)->send(new ActivateUser2($user));

        return $user;
    }

    public function hasSubdomain($url) {
        $parsed = parse_url($url);
        $exploded = explode('.', $parsed["host"]);
        return (count($exploded) > 2);
    }

    protected function registered(Request $request, $user)
    {
        //$this->guard()->logout();
        return redirect('/');//->with('status', 'Your account is successfully created. You can login now.');
    }

    public function activateUser($token)
    {
        $user = User::where('activation_token', $token)->first();
        if(isset($user) ){;
            if(!$user->status) {
                $user->status = 1;
                $user->save();
                //update user details
                $user_details = new User_Details;
                $user_details->user_id = $user->id;
                $user_details->privacy = 0;
                $user_details->country = 'IN';
                $user_details->city = '';
                $user_details->save();

                $status = "Your e-mail is verified. You can now login.";
            }else{
                $status = "Your e-mail is already verified. You can now login.";
            }
        }else{
            return redirect('/login')->with('warning', "Sorry your account cannot be identified. Kindly contact administrator");
        }
 
        return redirect('/login')->with('status', $status);
    }
 
}
