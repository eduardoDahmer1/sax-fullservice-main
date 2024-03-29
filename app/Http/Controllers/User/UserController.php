<?php

namespace App\Http\Controllers\User;

use Carbon\Carbon;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Subscription;
use App\Models\Verification;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\FavoriteSeller;
use App\Models\Generalsetting;
use App\Models\UserSubscription;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if (Auth::user()->IsVendor()) {
            return redirect()->route('vendor-dashboard');
        }
        return view('user.dashboard', compact('user'));
    }

    public function profile()
    {
        $user = Auth::user();
        $countries = Country::all();
        if (Auth::user()->IsVendor()) {
            return redirect()->route('front.index');
        }
        return view('user.profile', compact('user'))->with(['countries' => $countries]);
    }



    public function profileupdate(Request $request)
    {
        //--- Validation Section

        $rules =
        [
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'email' => 'unique:users,email,'.Auth::user()->id,
            'document' => 'numeric'
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
        $input = $request->all();

        $city = City::where('id', '=', $request->city_id)->first();
        $state = State::where('id', '=', $request->state_id)->first();
        $country = Country::where('id', '=', $request->country_id)->first();
        $input['city'] = $city->name;
        $input['city_id'] = $request->city_id;
        $input['state'] = $state->name;
        $input['state_id'] = $request->state_id;
        $input['country_id'] = $request->country_id;
        $input['country'] = $country->country_name;

        if (!$request->zip) {
            $input['zip'] = '0123';
        }

        /** @var User $data */
        $data = Auth::user();

        $data->birth_date = $input['birthday'];

        if ($file = $request->file('photo')) {
            $name = time().$file->getClientOriginalName();
            $file->move('storage/images/users/', $name);
            if ($data->photo != null) {
                if (file_exists(public_path().'/storage/images/users/'.$data->photo)) {
                    unlink(public_path().'/storage/images/users/'.$data->photo);
                }
            }
            $input['photo'] = $name;
        }
        $data->update($input);

        $msg = __('Successfully updated your profile');
        return response()->json($msg);
    }

    public function resetform()
    {
        if (Auth::user()->IsVendor()) {
            return view('vendor.reset');
        }
        return view('user.reset');
    }

    public function reset(Request $request)
    {
        $user = Auth::user();
        if ($request->cpass) {
            if (Hash::check($request->cpass, $user->password)) {
                if ($request->newpass == $request->renewpass) {
                    $input['password'] = Hash::make($request->newpass);
                } else {
                    return response()->json(array('errors' => [ 0 => __('Confirm password does not match.') ]));
                }
            } else {
                return response()->json(array('errors' => [ 0 => __('Current password Does not match.') ]));
            }
        }
        $user->update($input);
        $msg = __('Successfully change your password');
        return response()->json($msg);
    }


    public function package()
    {
        if (!config("features.marketplace")) {
            return redirect()->route("user-dashboard")->withErrors("Marketplace not enabled");
        }

        $user = Auth::user();
        $subs = Subscription::all();
        $package = $user->subscribes()->where('status', 1)->orderBy('id', 'desc')->first();
        return view('user.package.index', compact('user', 'subs', 'package'));
    }


    public function vendorrequest($id)
    {
        $subs = Subscription::findOrFail($id);
        $gs = Generalsetting::findOrfail(1);
        $user = Auth::user();
        $package = $user->subscribes()->where('status', 1)->orderBy('id', 'desc')->first();
        if ($gs->reg_vendor != 1) {
            return redirect()->back();
        }
        return view('user.package.details', compact('user', 'subs', 'package'));
    }

    public function vendorrequestsub(Request $request)
    {
        $this->validate($request, [
            'shop_name'   => 'unique:users',
        ], [
            'shop_name.unique' => __('This shop name has already been taken.')
        ]);

        $user = Auth::user();
        $package = $user->subscribes()->where('status', 1)->orderBy('id', 'desc')->first();
        $subs = Subscription::findOrFail($request->subs_id);
        $firstCurrency = Currency::find(1);
        $settings = Generalsetting::findOrFail(1);
        $today = Carbon::now()->format('Y-m-d');

        $input = $request->all();

        $user->is_vendor = 2;
        $user->date = date('Y-m-d', strtotime($today . ' + ' . $subs->days . ' days'));
        $user->mail_sent = 1;
        $user->is_vendor_verified = 0;

        $user->update($input);

        /* Delete all old verifications for this user */
        Verification::where('user_id', $user->id)->delete();

        /* Delete all previous subscriptions for this user */
        UserSubscription::where('user_id', $user->id)->delete();

        $sub = new UserSubscription;

        $sub->user_id = $user->id;
        $sub->subscription_id = $subs->id;
        $sub->title = $subs->title;
        $sub->currency = $firstCurrency->sign;
        $sub->currency_code = $firstCurrency->name;
        $sub->price = $subs->price;
        $sub->days = $subs->days;
        $sub->allowed_products = $subs->allowed_products;
        $sub->details = $subs->details;
        $sub->method = 'Store';
        $sub->status = 1;

        $sub->save();

        if ($settings->is_smtp == 1) {
            $data = [
                'to' => $user->email,
                'type' => "vendor_accept",
                'cname' => $user->name,
                'oamount' => "",
                'aname' => "",
                'aemail' => "",
                'onumber' => "",
            ];
            $mailer = new GeniusMailer();
            $mailer->sendAutoMail($data);
        } else {
            $headers = "From: " . $settings->from_name . "<" . $settings->from_email . ">";
            mail($user->email, 'Your Vendor Account Activated', 'Your Vendor Account Activated Successfully. Please Login to your account and build your own shop.', $headers);
        }

        return redirect()->route('vendor-verify');
    }


    public function favorite($id1, $id2)
    {
        $fav = new FavoriteSeller();
        $fav->user_id = $id1;
        $fav->vendor_id = $id2;
        $fav->save();
    }

    public function favorites()
    {
        $user = Auth::guard('web')->user();
        $favorites = FavoriteSeller::where('user_id', '=', $user->id)->get();
        return view('user.favorite', compact('user', 'favorites'));
    }


    public function favdelete($id)
    {
        $wish = FavoriteSeller::findOrFail($id);
        $wish->delete();
        return redirect()->route('user-favorites')->with('success', 'Successfully Removed The Seller.');
    }
}
