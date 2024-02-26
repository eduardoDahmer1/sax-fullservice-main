<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Validator;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\Generalsetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest:admin', ['except' => ['logout']]);
      parent::__construct();
    }

    public function showLoginForm()
    {
      return view('admin.login');
    }

    public function login(Request $request)
    {
        //--- Validation Section
        $rules = [
                  'email'   => 'required|email',
                  'password' => 'required'
                ];

        $validator = Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $redirectUrl = url()->route('admin.dashboard');
            $decodedUrl = urldecode($redirectUrl);
            return response()->json($decodedUrl);
        }      
        // Attempt to log the user in

        // if unsuccessful, then redirect back to the login with the form data
        return response()->json(array('errors' => [ 0 => __("Credentials Doesn't Match !") ]));     
    }

    public function showForgotForm()
    {
      return view('admin.forgot');
    }

    public function forgot(Request $request)
    {
      $gs = Generalsetting::findOrFail(1);
      $input =  $request->all();
      if (Admin::where('email', '=', $request->email)->count() > 0) {
      // user found
      $admin = Admin::where('email', '=', $request->email)->firstOrFail();
      $autopass = Str::random(8);
      $input['password'] = bcrypt($autopass);
      $admin->update($input);
      $subject = __("Reset Password Request");
      $msg = __("Your New Password is :").$autopass;
      if($gs->is_smtp == 1)
      {
          $data = [
                  'to' => $request->email,
                  'subject' => $subject,
                  'body' => $msg,
          ];

          $mailer = new GeniusMailer();
          $mailer->sendCustomMail($data);                
      }
      else
      {
          $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
          mail($request->email,$subject,$msg,$headers);            
      }
      return response()->json(__('Your Password Reseted Successfully. Please Check your email for new Password.'));
      }
      else{
      // user not found
      return response()->json(array('errors' => [ 0 => __('No Account Found With This Email.') ]));    
      }  
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/');
    }
}
