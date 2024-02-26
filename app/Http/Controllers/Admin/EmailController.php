<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\DataTables;
use App\Models\User;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Classes\GeniusMailer;
use App\Models\EmailTemplate;
use App\Models\Generalsetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class EmailController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:admin');
        parent::__construct();
    }

    //*** JSON Request
    public function datatables()
    {
        $datas = EmailTemplate::orderBy('id', 'desc');
        //--- Integrating This Collection Into Datatables
        return Datatables::of($datas)
            ->filterColumn('email_subject', function ($query, $keyword) {
                $query->whereTranslationLike('email_subject', "%{$keyword}%", $this->lang->locale);
            })
            ->addColumn('action', function (EmailTemplate $data) {
                return '<div class="action-list"><a data-href="' . route('admin-mail-edit', $data->id) . '" data-header="' . __('Edit Email Template') . '" class="edit" data-toggle="modal" data-target="#modal1"> <i class="fas fa-edit"></i>' . __('Edit') . '</a></div>';
            })
            ->toJson(); //--- Returning Json Data To Client Side
    }

    public function index()
    {
        return view('admin.email.index');
    }

    public function config()
    {
        return view('admin.email.config');
    }

    public function edit($id)
    {
        $data = EmailTemplate::findOrFail($id);
        return view('admin.email.edit',compact('data'));
    }

    public function groupemail()
    {
        $config = Generalsetting::findOrFail(1);
        return view('admin.email.group',compact('config'));
    }

    public function groupemailpost(Request $request)
    {
        $config = Generalsetting::findOrFail(1);
        if($request->type == 0)
        {
        $users = User::all();
        //Sending Email To Users
        foreach($users as $user)
        {
            if($config->is_smtp == 1)
            {
                $data = [
                    'to' => $user->email,
                    'subject' => $request->subject,
                    'body' => $request->body,
                ];

                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);            
            }
            else
            {
               $to = $user->email;
               $subject = $request->subject;
               $msg = $request->body;
                $headers = "From: ".$config->from_name."<".$config->from_email.">";
               mail($to,$subject,$msg,$headers);
            }  
        } 
        //--- Redirect Section          
        $msg = __('Email Sent Successfully.');
        return response()->json($msg);    
        //--- Redirect Section Ends  
        }

        else if($request->type == 1)
        {
            $users = User::where('is_vendor','=','2')->get();
            //Sending Email To Vendors        
            foreach($users as $user)
            {
                if($config->is_smtp == 1)
                {
                    $data = [
                        'to' => $user->email,
                        'subject' => $request->subject,
                        'body' => $request->body,
                    ];

                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($data);            
                }
                else
                {
                $to = $user->email;
                $subject = $request->subject;
                $msg = $request->body;
                    $headers = "From: ".$config->from_name."<".$config->from_email.">";
                mail($to,$subject,$msg,$headers);
                }  
            }
        } 
        else
        {
            $users = Subscriber::all();
            //Sending Email To Subscribers
            foreach($users as $user)
            {
                if($config->is_smtp == 1)
                {
                    $data = [
                        'to' => $user->email,
                        'subject' => $request->subject,
                        'body' => $request->body,
                    ];

                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($data);            
                }
                else
                {
                $to = $user->email;
                $subject = $request->subject;
                $msg = $request->body;
                    $headers = "From: ".$config->from_name."<".$config->from_email.">";
                mail($to,$subject,$msg,$headers);
                }  
            }   
        }

        //--- Redirect Section          
        $msg = __('Email Sent Successfully.');
        return response()->json($msg);    
        //--- Redirect Section Ends  
    }


    

    public function update(Request $request, $id)
    {
        //--- Validation Section
        $rules = [
            "{$this->lang->locale}.email_subject" => 'required',
            "{$this->lang->locale}.email_body" => 'required',
                 ];
        $customs = [
            "{$this->lang->locale}.email_subject.required" => __('Email Subject in :lang is required',['lang' => $this->lang->language]),
            "{$this->lang->locale}.email_body.required" => __('Email Body in :lang is required',['lang' => $this->lang->language]),
                   ];
        $validator = Validator::make($request->all(), $rules, $customs);

        if ($validator->fails()) {
          return response()->json(array('errors' => $validator->getMessageBag()->toArray()));
        }
        //--- Validation Section Ends

        $data = EmailTemplate::findOrFail($id);
        $input = $this->removeEmptyTranslations($request->all(), $data);
        $data->update($input);
        //--- Redirect Section          
        $msg = __('Data Updated Successfully.');
        return response()->json($msg);    
        //--- Redirect Section Ends  
    }

}
