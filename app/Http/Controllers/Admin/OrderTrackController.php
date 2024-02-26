<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderTrack;
use Validator;

class OrderTrackController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }


   //*** GET Request
    public function index($id)
    {
    	$order = Order::findOrFail($id);
        return view('admin.order.track',compact('order'));
    }

   //*** GET Request
    public function load($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.order.track-load',compact('order'));
    }


    public function add()
    {


        //--- Logic Section

        $title = $_GET['title'];

        $ck = OrderTrack::where('order_id','=',$_GET['id'])->where('title','=',$title)->first();
        if($ck){
            $ck->order_id = $_GET['id'];
            $ck->title = $_GET['title'];
            $ck->text = $_GET['text'];
            $ck->update();  
        }
        else {
            $data = new OrderTrack;
            $data->order_id = $_GET['id'];
            $data->title = $_GET['title'];
            $data->text = $_GET['text'];
            $data->save();            
        }


        //--- Logic Section Ends


    }


    //*** POST Request
    public function store(Request $request)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Tracking Title in :lang is required',['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        $input = $this->withRequiredFields($request->all(), ['title']);
        $data = new OrderTrack;
        $data->fill($input)->save();

        //--- Redirect Section  
        $msg = __('New Data Added Successfully.');
        return response()->json($msg);
        //--- Redirect Section Ends  
    }


    //*** POST Request
    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.title" => 'required',
        ];
        $customs = [
            "{$this->lang->locale}.title.required" => __('Tracking Title in :lang is required',['lang' => $this->lang->language]),
        ];

        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
            return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        //--- Logic Section
        $data = OrderTrack::findOrFail($id);
        $input = $this->withRequiredFields($request->all(), ['title']);
        $data->update($input);
        //--- Logic Section Ends

        //--- Redirect Section          
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);    
        //--- Redirect Section Ends  

    }

    //*** GET Request
    public function delete($id)
    {
        $data = OrderTrack::findOrFail($id);
        $data->delete();
        //--- Redirect Section     
        $msg = __('Data Deleted Successfully.');
        return response()->json($msg);      
        //--- Redirect Section Ends   
    }

}
