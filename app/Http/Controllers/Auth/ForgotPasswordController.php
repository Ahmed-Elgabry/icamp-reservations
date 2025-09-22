<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use App\Mail\ResetPassword;
use Illuminate\Http\Request;
use Mail;
use Illuminate\Support\Str;
use App\Models\User;
use DB;
use Hash;
use Carbon\Carbon; 


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function showResetForm(Request $request)
    {
        $token = $request->token;
        $email = DB::table('password_resets')->where('token',$token)->first();
      
        if(!$email)
        {
            return redirect(route('login'))->withDanger('رابط غير صالح');
        }

        $email = $email->email;
        

        return view('auth.passwords.reset', compact('token','email')); // Return the view for password reset form
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Str::random(60); // Generate a random string for the token


            DB::table('password_resets')->updateOrInsert([
                'email' => $user->email,
            ], [
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);

            $actionUrl = route('password.reset', ['token' => $token]);
    
            Mail::to($user)->send(new ResetPassword($actionUrl));
            
            
            return back()->withSuccess('تم أرسال الايميل بنجاح');
            // Other logic
        } else {
           return back()->withDanger('الايميل غير موجود او هنالك خطأ ما');
        }

    }


    public function reset(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8', 
        ]);
        

        $tokenData = DB::table('password_resets')->where('email', $request->email)->first();

         if ($tokenData) {
            // Check if the token matches
            if ( $request->token == $tokenData->token) {
                // Perform the password update
                DB::table('users')
                    ->where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);
    
                // Remove the token from password_resets table
                DB::table('password_resets')->where('email', $request->email)->delete();
    
                return redirect()->route('login')->with('success', "تم تعديل كلمة السر بنجاح");
            } else {
                return back()->with('danger', "رابط غير صالح ");
            }
        } else {
            return redirect()->route('login')->with('danger', "رابط غير صالح ");
        }

        
    }



}
