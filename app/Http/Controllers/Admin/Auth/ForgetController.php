<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

// password reset to import
use Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Admin;

class ForgetController extends Controller
{
    public function password_reset(){
        return view('admin.auth.password_reset');
    }
    public function password_reset_submit(Request $request){
        dd($request->all());
    }

    function reset_password_submit(Request $request){

        $request->validate([
            'email'=>'required | email | exists:admins',   
        ]);
    
        $token = Str::random(64);
    
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);
    

      $action_link = route('admin.reset.password.form',['token'=>$token,'email'=>$request->email]);
      $body = "We are received a request to reset the password for <b>Your app Name </b> account associated with ".$request->email.". You can reset your password by clicking the link below";

     Mail::send('admin.auth.mailforget',['action_link'=>$action_link,'body'=>$body], function($message) use ($request){
           $message->from('umait@umait.xyz','Your App Name');
           $message->to($request->email,'Your name')
                   ->subject('Reset Password');
     });
    
      return back()->with('success','we have e-mailed your password rest link!');
    }

    function show_reset_password_form(Request $request){
        $token = $request->token;
        $email = $request->email;
        return view('admin.auth.password_reset_form',['token'=>$token,'email'=>$email]);
     }



    
    function new_password_submit(Request $request){
        $request->validate([
            'email'=>'required | email | exists:admins', 
            'password'=>'required | string | min:8',   
            'confirm_password'=>'required',  
        ]);
    
        $updatepassword = DB::table('password_resets')->where([
            'email'=>$request->email,
            'token'=>$request->token
        ])->first();
     
        if(!$updatepassword){
            return back()->with('error','Invalid');
        }else{
            $responce = Admin::where('email',$request->email)->update(['password'=>Hash::make($request->password)]);
            
            if($responce){
                DB::table('password_resets')->where('email',$request->email)->delete();
                return redirect('admin/login');
            }
        }
    }
}
