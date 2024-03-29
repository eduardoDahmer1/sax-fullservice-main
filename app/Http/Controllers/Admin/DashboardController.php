<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use App\Models\User;
use App\Models\Order;
use App\Models\Counter;
use App\Models\Product;
use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    public function index()
    {
        $pending = Order::where('status', '=', 'pending')->count();
        $processing = Order::where('status', '=', 'processing')->count();
        $completed = Order::where('status', '=', 'completed')->count();
        $days = "";
        $sales = "";
        for ($i = 0; $i < 30; $i++) {
            $days .= "'".date("d M", strtotime('-'. $i .' days'))."',";

            $sales .=  "'".Order::where('status', '=', 'completed')->whereDate('created_at', '=', date("Y-m-d", strtotime('-'. $i .' days')))->count()."',";
        }
        $users_total = User::count();
        $products_total = Product::count();
        $blogs_total = Blog::count();
        $pproducts = Product::orderBy('id', 'desc')->take(5)->get();
        $rorders = Order::orderBy('id', 'desc')->take(5)->get();
        $poproducts = Product::orderBy('views', 'desc')->take(5)->get();
        $rusers = User::orderBy('id', 'desc')->take(5)->get();
        $referrals = Counter::where('type', 'referral')->orderBy('total_count', 'desc')->take(5)->get();
        $browsers = Counter::where('type', 'browser')->orderBy('total_count', 'desc')->take(5)->get();



        return view('admin.dashboard', compact('pending', 'processing', 'completed', 'products_total', 'users_total', 'blogs_total', 'days', 'sales', 'pproducts', 'rorders', 'poproducts', 'rusers', 'referrals', 'browsers'));
    }

    public function profile()
    {
        $data = Auth::guard('admin')->user();
        return view('admin.profile', compact('data'));
    }

    public function profileupdate(Request $request)
    {
        //--- Validation Section

        $rules =
        [
            'photo' => 'mimes:jpeg,jpg,png,svg',
            'email' => 'unique:admins,email,'.Auth::guard('admin')->user()->id
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends
        $input = $request->all();
        /** @var User $data */
        $data = Auth::guard('admin')->user();
        if ($file = $request->file('photo')) {
            $name = time().$file->getClientOriginalName();
            $file->move('storage/images/admins/', $name);
            if ($data->photo != null) {
                if (file_exists(public_path().'/storage/images/admins/'.$data->photo)) {
                    unlink(public_path().'/storage/images/admins/'.$data->photo);
                }
            }
            $input['photo'] = $name;
        }
        $data->update($input);
        $msg = __('Successfully updated your profile');
        return response()->json($msg);
    }

    public function passwordreset()
    {
        $data = Auth::guard('admin')->user();
        return view('admin.password', compact('data'));
    }

    public function changepass(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        if ($request->cpass) {
            if (Hash::check($request->cpass, $admin->password)) {
                if ($request->newpass == $request->renewpass) {
                    $input['password'] = Hash::make($request->newpass);
                } else {
                    return response()->json(array('errors' => [ 0 => __('Confirm password does not match.') ]));
                }
            } else {
                return response()->json(array('errors' => [ 0 => __('Current password Does not match.') ]));
            }
        }
        $admin->update($input);
        $msg = __('Successfully change your password');
        return response()->json($msg);
    }



    public function generate_bkup()
    {
        $bkuplink = "";
        $chk = file_get_contents('backup.txt');
        if ($chk != "") {
            $bkuplink = url($chk);
        }
        return view('admin.movetoserver', compact('bkuplink', 'chk'));
    }


    public function clear_bkup()
    {
        $destination  = public_path().'/install';
        $bkuplink = "";
        $chk = file_get_contents('backup.txt');
        if ($chk != "") {
            unlink(public_path($chk));
        }

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }
        $handle = fopen('backup.txt', 'w+');
        fwrite($handle, "");
        fclose($handle);
        //return "No Backup File Generated.";
        return redirect()->back()->with('success', __('Backup file Deleted Successfully!'));
    }


    public function setUp($mtFile, $goFileData)
    {
        $fpa = fopen(public_path().$mtFile, 'w');
        fwrite($fpa, $goFileData);
        fclose($fpa);
    }



    public function movescript()
    {
        ini_set('max_execution_time', 3000);

        $destination  = public_path().'/install';
        $chk = file_get_contents('backup.txt');
        if ($chk != "") {
            unlink(public_path($chk));
        }

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }

        $src = base_path().'/vendor/update';
        $this->recurse_copy($src, $destination);
        $files = public_path();
        $bkupname = 'bkcup_crow-'.date('Y-m-d').'.zip';

        $zipper = new \Chumper\Zipper\Zipper;

        $zipper->make($bkupname)->add($files);

        $zipper->remove($bkupname);

        $zipper->close();

        $handle = fopen('backup.txt', 'w+');
        fwrite($handle, $bkupname);
        fclose($handle);

        if (is_dir($destination)) {
            $this->deleteDir($destination);
        }
        return response()->json(['status' => 'success','backupfile' => url($bkupname),'filename' => $bkupname], 200);
    }

    public function recurse_copy($src, $dst)
    {
        $dir = opendir($src);
        @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public function deleteDir($dirPath)
    {
        if (! is_dir($dirPath)) {
            throw new InvalidArgumentException("$dirPath ".__('must be a directory'));
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }

    public function deleteImage()
    {
        $data = Auth::guard('admin')->user();
        if ($data->photo != null) {
            if (file_exists(public_path().'/storage/images/admins/'.$data->photo)) {
                unlink(public_path().'/storage/images/admins/'.$data->photo);
                $input['photo'] = '';
                $data->update($input);
                $msg = __('Image Deleted Successfully');
                return response()->json([
                    'status'=>true,
                    'message' => $msg
                ]);
            }
        }
    }
}
