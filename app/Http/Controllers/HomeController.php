<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Models;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Sabberworm\CSS\Settings;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect('/admin');
        // return view('home');
    }

    public function changepassword()
    {
        if (Gate::allows('is_admin')) {
            return view('changepassword');
        } else {
            return abort(401);
        }
    }


    public function updatepassword(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $user = Auth::user();
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'The current password is incorrect'])->withInput();
            }
            $user->password = Hash::make($request->new_password);
            $user->save();

            return redirect()->back()->with('success', 'Password updated successfully');
        } else {
            return abort(401);
        }
    }



    public function checkmodel(Request $request)
    {
        if (Gate::allows('is_admin')) {
            $response = null;
            if ($request->imei) {
                $myCheck["service"] = 0;
                $myCheck["imei"] = $request->imei;
                $myCheck["key"] = "P1E-Q49-HCL-L4B-A7I-VYC-LDA-U8O";
                $ch = curl_init("https://api.ifreeicloud.co.uk");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $myCheck);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                $myResult = json_decode(curl_exec($ch));
                $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                if ($httpcode != 200) {
                    return redirect()->back()->with('error', $httpcode);
                } elseif ($myResult->success !== true) {
                    return redirect()->back()->with('error', $myResult->error);
                } else {
                    $response = $myResult->response;
                }
            }
        } else {
            return abort(401);
        }

        return view('checkmodel', ['response' => $response]);
    }
}
