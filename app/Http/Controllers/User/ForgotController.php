<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\Generalsetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

class ForgotController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    public function showForgotForm()
    {
      return view('user.forgot');
    }

    public function forgot(Request $request)
    {
      $gs = Generalsetting::findOrFail(1);
      $input =  $request->all();
      if (User::where('email', '=', $request->email)->count() > 0) {
      // user found
      $admin = User::where('email', '=', $request->email)->firstOrFail();
      $token = md5(time().$request->email);
      $input['password_reset'] = $token;
      $admin->update($input);
      $subject = __("Reset Password Request");
      $msg = __("Dear Customer").",<br> ".__("We noticed that you need to reset your password.")." <a href=".url('user/reset/'.$token).">".__("Click here to change your password.")." </a>";
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
      return response()->json(__('An email with a link to redefine your password was sent.'));
      }
      else{
      // user not found
      return response()->json(array('errors' => [ 0 => __('No Account Found With This Email.') ]));    
      }
    }

    public function showPasswordForm($token)
    { 	
        $user = User::where('password_reset','=',$token)->firstOrFail();

    		return view('user.reset-password')->with(['token' => $token]);
    }

  public function resetPassword(Request $request, $token)
  {

    $rules = [
      'password' => 'required',
      'renewpass' => 'required|same:password'
    ];
    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
    }
    //--- Validation Section Ends

    $password = $request->password;
    $tokenData = User::where('password_reset', $token)->firstOrFail();

    $user = User::where([
      'password_reset' => $token,
      'email' => $tokenData->email
      ])->firstOrFail();

    $user->password = Hash::make($password);
    $user->password_reset = null;
    $user->update();

      //--- Redirect Section
      $msg = __('Password reseted successfully. You can login with your new password now.');
      return response()->json($msg);
      //--- Redirect Section Ends
  }
}


